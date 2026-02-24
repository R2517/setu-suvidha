<?php

namespace App\Http\Controllers;

use App\Models\FarmerCardOrder;
use App\Models\FormPricing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FarmerCardPublicController extends Controller
{
    /**
     * SEO Blog / Landing page
     */
    /**
     * Get service pricing from DB
     */
    private function getServicePrice(): float
    {
        $pricing = FormPricing::where('form_type', 'farmer_id_card_public')->first();
        return $pricing ? (float) $pricing->price : 0;
    }

    public function index()
    {
        $districts = config('maharashtra.districts');
        $servicePrice = $this->getServicePrice();
        return view('public.farmer-id-card', compact('districts', 'servicePrice'));
    }

    /**
     * Store order + create Razorpay order
     */
    public function store(Request $request)
    {
        $request->validate([
            'applicant_name' => 'required|string|max:255',
            'name_english'   => 'required|string|max:255',
            'mobile'         => 'required|digits:10',
            'dob'            => 'nullable|date',
            'gender'         => 'nullable|in:Male,Female,Other',
            'aadhaar'        => 'nullable|string|max:14',
            'farmer_id'      => 'nullable|string|max:20',
            'address_village' => 'nullable|string|max:255',
            'address_taluka'  => 'nullable|string|max:255',
            'address_district' => 'nullable|string|max:255',
            'address_pincode'  => 'nullable|digits:6',
            'photo'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('farmer_public_photos', 'public');
        }

        // Parse land details from request
        $lands = [];
        if ($request->has('land')) {
            foreach ($request->input('land', []) as $l) {
                if (!empty($l['district']) || !empty($l['village'])) {
                    $lands[] = [
                        'district' => $l['district'] ?? '',
                        'taluka'   => $l['taluka'] ?? '',
                        'village'  => $l['village'] ?? '',
                        'gat_no'   => $l['gat_no'] ?? '',
                        'khate_no' => $l['khate_no'] ?? '',
                        'area'     => $l['area'] ?? '',
                    ];
                }
            }
        }

        $txn = FarmerCardOrder::generateTransactionNo();
        $servicePrice = $this->getServicePrice();
        $amountPaise = (int) ($servicePrice * 100); // Convert ₹ to paise

        $order = FarmerCardOrder::create([
            'transaction_no'   => $txn,
            'applicant_name'   => $request->applicant_name,
            'name_english'     => $request->name_english,
            'dob'              => $request->dob,
            'gender'           => $request->gender,
            'mobile'           => $request->mobile,
            'aadhaar'          => $request->aadhaar,
            'farmer_id'        => $request->farmer_id,
            'address_village'  => $request->address_village,
            'address_taluka'   => $request->address_taluka,
            'address_district' => $request->address_district,
            'address_pincode'  => $request->address_pincode,
            'photo'            => $photoPath,
            'land_details'     => $lands,
            'amount'           => $amountPaise,
            'status'           => $amountPaise === 0 ? 'paid' : 'pending',
            'ip_address'       => $request->ip(),
        ]);

        // If FREE (price=0), skip Razorpay — direct download
        if ($amountPaise === 0) {
            return response()->json([
                'success'        => true,
                'free'           => true,
                'transaction_no' => $txn,
                'download_url'   => route('farmer-card-public.download', $txn),
            ]);
        }

        // Create Razorpay order for paid service
        $razorpayOrderId = null;
        try {
            $keyId = config('razorpay.key_id');
            $keySecret = config('razorpay.key_secret');

            if ($keyId && $keySecret) {
                $api = new \Razorpay\Api\Api($keyId, $keySecret);
                $rpOrder = $api->order->create([
                    'receipt'  => $txn,
                    'amount'   => $amountPaise,
                    'currency' => 'INR',
                    'notes'    => [
                        'type' => 'farmer_card_public',
                        'txn'  => $txn,
                    ],
                ]);
                $razorpayOrderId = $rpOrder->id;
                $order->update(['razorpay_order_id' => $razorpayOrderId]);
            }
        } catch (\Exception $e) {
            Log::error('Razorpay order creation failed', [
                'txn' => $txn,
                'error' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'success'           => true,
            'free'              => false,
            'transaction_no'    => $txn,
            'order_id'          => $order->id,
            'razorpay_order_id' => $razorpayOrderId,
            'razorpay_key'      => config('razorpay.key_id'),
            'amount'            => $amountPaise,
            'name'              => $order->name_english,
            'mobile'            => $order->mobile,
        ]);
    }

    /**
     * Verify Razorpay payment
     */
    public function verifyPayment(Request $request)
    {
        $request->validate([
            'transaction_no'       => 'required|string',
            'razorpay_payment_id'  => 'required|string',
            'razorpay_order_id'    => 'required|string',
            'razorpay_signature'   => 'required|string',
        ]);

        $order = FarmerCardOrder::where('transaction_no', $request->transaction_no)->firstOrFail();

        if ($order->status === 'paid' && $order->razorpay_payment_id === $request->razorpay_payment_id) {
            return response()->json([
                'success' => true,
                'transaction_no' => $order->transaction_no,
                'message' => 'Payment already verified.',
            ]);
        }

        if ($order->status === 'paid' && $order->razorpay_payment_id !== $request->razorpay_payment_id) {
            return response()->json(['success' => false, 'message' => 'Order is already paid.'], 409);
        }

        if (!$order->razorpay_order_id || $order->razorpay_order_id !== $request->razorpay_order_id) {
            return response()->json(['success' => false, 'message' => 'Order mismatch.'], 400);
        }

        // Verify signature
        try {
            $keySecret = config('razorpay.key_secret');
            $expectedSignature = hash_hmac('sha256', $request->razorpay_order_id . '|' . $request->razorpay_payment_id, $keySecret);

            if (!hash_equals($expectedSignature, $request->razorpay_signature)) {
                return response()->json(['success' => false, 'message' => 'Payment verification failed.'], 400);
            }

            $api = new \Razorpay\Api\Api(config('razorpay.key_id'), $keySecret);
            $payment = $api->payment->fetch($request->razorpay_payment_id);

            if (($payment['order_id'] ?? null) !== $order->razorpay_order_id) {
                return response()->json(['success' => false, 'message' => 'Payment/order mismatch.'], 400);
            }

            if ((int) ($payment['amount'] ?? 0) !== (int) $order->amount) {
                return response()->json(['success' => false, 'message' => 'Payment amount mismatch.'], 400);
            }

            if (strtolower((string) ($payment['status'] ?? '')) !== 'captured') {
                return response()->json(['success' => false, 'message' => 'Payment is not captured yet.'], 400);
            }

            $order->update([
                'razorpay_payment_id' => $request->razorpay_payment_id,
                'status' => 'paid',
            ]);

            return response()->json([
                'success'        => true,
                'transaction_no' => $order->transaction_no,
                'message'        => 'Payment successful! Your card is ready to download.',
            ]);
        } catch (\Exception $e) {
            Log::error('Payment verification error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Verification error.'], 500);
        }
    }

    /**
     * Download / view card by transaction number
     */
    public function download($txn)
    {
        $order = FarmerCardOrder::where('transaction_no', $txn)
            ->where('status', 'paid')
            ->firstOrFail();

        if ($order->data_purged) {
            abort(410, 'Card data has been purged after 7 days. Contact admin for records.');
        }

        $order->increment('download_count');
        $order->update(['last_downloaded_at' => now()]);

        $districts = config('maharashtra.districts');
        return view('public.farmer-id-card-print', compact('order', 'districts'));
    }

    /**
     * Lookup by transaction number (AJAX)
     */
    public function lookup(Request $request)
    {
        $request->validate(['transaction_no' => 'required|string|max:25']);

        $order = FarmerCardOrder::where('transaction_no', $request->transaction_no)->first();

        if (!$order) {
            return response()->json(['found' => false, 'message' => 'Transaction number not found.']);
        }

        if ($order->status !== 'paid') {
            return response()->json(['found' => true, 'paid' => false, 'message' => 'Payment pending for this order.']);
        }

        if ($order->data_purged) {
            return response()->json(['found' => true, 'paid' => true, 'purged' => true, 'message' => 'Card data expired (7 days). Contact admin.']);
        }

        return response()->json([
            'found'          => true,
            'paid'           => true,
            'purged'         => false,
            'transaction_no' => $order->transaction_no,
            'name'           => $order->applicant_name,
            'created_at'     => $order->created_at->format('d M Y, h:i A'),
            'downloads'      => $order->download_count,
            'download_url'   => route('farmer-card-public.download', $order->transaction_no),
        ]);
    }
}
