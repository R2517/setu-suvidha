<?php

namespace App\Http\Controllers;

use App\Models\BillingSale;
use App\Models\BillingSaleItem;
use App\Models\BillingExpense;
use App\Models\BillingMonthlyExpense;
use App\Models\BillingCustomer;
use App\Models\BillingService;
use App\Models\BillingDailyBook;
use App\Models\BillingKioskTransaction;
use App\Models\BillingCashAdjustment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BillingController extends Controller
{
    // ==================== DASHBOARD ====================

    public function dashboard(Request $request)
    {
        $userId = $request->user()->id;
        $period = $request->get('period', 'today');
        $today = Carbon::today();

        switch ($period) {
            case 'week':
                $startDate = $today->copy()->startOfWeek();
                $endDate = $today->copy()->endOfWeek();
                break;
            case 'month':
                $startDate = $today->copy()->startOfMonth();
                $endDate = $today->copy()->endOfMonth();
                break;
            default:
                $startDate = $today->copy();
                $endDate = $today->copy();
        }

        // Metric Cards
        $salesTotal = BillingSale::where('user_id', $userId)->active()
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->sum('total_amount');

        $expensesTotal = BillingExpense::where('user_id', $userId)
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->sum('amount');

        $pendingPayments = BillingSale::where('user_id', $userId)->active()
            ->whereIn('payment_status', ['unpaid', 'partial'])
            ->sum('due_amount');

        $totalProfit = $salesTotal - $expensesTotal;

        // Kiosk Summary (if enabled)
        $profile = $request->user()->profile;
        $kioskSummary = null;
        if ($profile->kiosk_enabled) {
            $kioskQuery = BillingKioskTransaction::where('user_id', $userId)
                ->whereBetween('transaction_date', [$startDate, $endDate]);

            $kioskSummary = [
                'withdrawals' => (clone $kioskQuery)->where('transaction_type', 'withdraw')->sum('amount'),
                'deposits' => (clone $kioskQuery)->where('transaction_type', 'deposit')->sum('amount'),
                'cash_commission' => (clone $kioskQuery)->sum('manual_commission'),
                'portal_commission' => (clone $kioskQuery)->sum('portal_commission'),
            ];

            if (array_sum($kioskSummary) == 0) $kioskSummary = null;
        }

        // Recent Transactions (last 5 sales)
        $recentSales = BillingSale::where('user_id', $userId)->active()
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Chart data: last 7 days sales vs expenses
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = $today->copy()->subDays($i);
            $daySales = BillingSale::where('user_id', $userId)->active()
                ->where('sale_date', $date->toDateString())->sum('total_amount');
            $dayExpenses = BillingExpense::where('user_id', $userId)
                ->where('expense_date', $date->toDateString())->sum('amount');
            $chartData[] = [
                'date' => $date->format('d M'),
                'sales' => (float) $daySales,
                'expenses' => (float) $dayExpenses,
            ];
        }

        // Category-wise sales breakdown
        $categoryData = BillingSaleItem::whereHas('sale', fn($q) => $q->where('user_id', $userId)->active()
                ->whereBetween('sale_date', [$startDate, $endDate]))
            ->select('service_name', DB::raw('SUM(total_price) as total'))
            ->groupBy('service_name')
            ->orderByDesc('total')
            ->take(8)
            ->get();

        return view('billing.dashboard', compact(
            'salesTotal', 'expensesTotal', 'pendingPayments', 'totalProfit',
            'kioskSummary', 'recentSales', 'chartData', 'categoryData', 'period', 'profile'
        ));
    }

    // ==================== SALES ====================

    public function sales(Request $request)
    {
        $userId = $request->user()->id;
        $query = BillingSale::where('user_id', $userId)->active()->with('items');

        if ($request->search) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('customer_name', 'like', "%$s%")
                  ->orWhere('customer_phone', 'like', "%$s%")
                  ->orWhere('invoice_number', 'like', "%$s%");
            });
        }

        if ($request->status && $request->status !== 'all') {
            $query->where('payment_status', $request->status);
        }

        if ($request->date_filter) {
            $today = Carbon::today();
            switch ($request->date_filter) {
                case 'today': $query->where('sale_date', $today); break;
                case 'week': $query->whereBetween('sale_date', [$today->startOfWeek(), $today->endOfWeek()]); break;
                case 'month': $query->whereBetween('sale_date', [$today->startOfMonth(), $today->endOfMonth()]); break;
            }
        }

        $sales = $query->orderBy('created_at', 'desc')->paginate($request->get('per_page', 25));
        $services = BillingService::where('user_id', $userId)->where('is_active', true)->orderBy('display_order')->get();
        $customers = BillingCustomer::where('user_id', $userId)->orderBy('name')->get();

        return view('billing.sales', compact('sales', 'services', 'customers'));
    }

    public function storeSale(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.service_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'payment_mode' => 'required|in:cash,upi,online,split',
        ]);

        $userId = $request->user()->id;

        // Generate invoice number
        $lastInvoice = BillingSale::where('user_id', $userId)->orderBy('id', 'desc')->first();
        $nextNum = $lastInvoice ? ((int) substr($lastInvoice->invoice_number ?? '0', -4)) + 1 : 1;
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);

        // Calculate totals
        $totalAmount = 0;
        $items = [];
        foreach ($request->items as $item) {
            $total = $item['quantity'] * $item['unit_price'];
            $discount = $item['discount_amount'] ?? 0;
            $totalAmount += ($total - $discount);
            $items[] = [
                'service_name' => $item['service_name'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'cost_price' => $item['cost_price'] ?? 0,
                'total_price' => $total,
                'discount_amount' => $discount,
            ];
        }

        $discountAmount = (float) ($request->discount_amount ?? 0);
        $totalAmount -= $discountAmount;
        $receivedAmount = (float) ($request->received_amount ?? $totalAmount);
        $dueAmount = max(0, $totalAmount - $receivedAmount);

        $paymentStatus = 'unpaid';
        if ($dueAmount <= 0) $paymentStatus = 'paid';
        elseif ($receivedAmount > 0) $paymentStatus = 'partial';

        // Find/Create customer
        $customerId = null;
        if ($request->customer_name || $request->customer_phone) {
            $customer = BillingCustomer::firstOrCreate(
                ['user_id' => $userId, 'mobile' => $request->customer_phone ?: null],
                ['name' => $request->customer_name ?: 'Guest', 'mobile' => $request->customer_phone]
            );
            if ($request->customer_name) $customer->update(['name' => $request->customer_name]);
            $customer->increment('total_visits');
            $customer->increment('total_spent', $receivedAmount);
            $customer->increment('total_due', $dueAmount);
            $customerId = $customer->id;
        }

        $sale = BillingSale::create([
            'user_id' => $userId,
            'customer_id' => $customerId,
            'invoice_number' => $invoiceNumber,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'total_amount' => $totalAmount,
            'discount_amount' => $discountAmount,
            'received_amount' => $receivedAmount,
            'due_amount' => $dueAmount,
            'payment_mode' => $request->payment_mode,
            'cash_amount' => $request->cash_amount ?? 0,
            'online_amount' => $request->online_amount ?? 0,
            'payment_status' => $paymentStatus,
            'remarks' => $request->remarks,
            'sale_date' => $request->sale_date ?? now()->toDateString(),
        ]);

        foreach ($items as $item) {
            $sale->items()->create($item);
        }

        return response()->json(['success' => true, 'sale' => $sale->load('items'), 'invoice_number' => $invoiceNumber]);
    }

    public function updateSale(Request $request, $id)
    {
        $sale = BillingSale::where('user_id', $request->user()->id)->findOrFail($id);

        $sale->update($request->only([
            'customer_name', 'customer_phone', 'received_amount', 'due_amount',
            'payment_mode', 'cash_amount', 'online_amount', 'payment_status', 'remarks',
        ]));

        // Recalculate payment status
        if ($request->has('received_amount')) {
            $due = max(0, $sale->total_amount - $request->received_amount);
            $status = 'unpaid';
            if ($due <= 0) $status = 'paid';
            elseif ($request->received_amount > 0) $status = 'partial';
            $sale->update(['due_amount' => $due, 'payment_status' => $status]);
        }

        return response()->json(['success' => true, 'sale' => $sale->fresh()->load('items')]);
    }

    public function softDeleteSale(Request $request, $id)
    {
        $sale = BillingSale::where('user_id', $request->user()->id)->findOrFail($id);
        $sale->update([
            'is_deleted' => true,
            'delete_reason' => $request->reason ?? 'Deleted by user',
            'deleted_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    public function restoreSale(Request $request, $id)
    {
        $sale = BillingSale::where('user_id', $request->user()->id)->findOrFail($id);
        $sale->update(['is_deleted' => false, 'delete_reason' => null, 'deleted_at' => null]);

        return response()->json(['success' => true]);
    }

    // ==================== EXPENSES ====================

    public function expenses(Request $request)
    {
        $userId = $request->user()->id;
        $monthlyExpenses = BillingMonthlyExpense::where('user_id', $userId)->orderBy('category')->get();
        $today = Carbon::today();

        $query = BillingExpense::where('user_id', $userId);
        if ($request->search) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }
        if ($request->category) {
            $query->where('category', $request->category);
        }

        $expenses = $query->orderBy('expense_date', 'desc')->paginate(25);

        // Summary
        $monthStart = $today->copy()->startOfMonth();
        $monthEnd = $today->copy()->endOfMonth();
        $monthDailyExpenses = BillingExpense::where('user_id', $userId)
            ->whereBetween('expense_date', [$monthStart, $monthEnd])->sum('amount');
        $monthSales = BillingSale::where('user_id', $userId)->active()
            ->whereBetween('sale_date', [$monthStart, $monthEnd])->sum('total_amount');

        return view('billing.expenses', compact('monthlyExpenses', 'expenses', 'monthDailyExpenses', 'monthSales'));
    }

    public function storeExpense(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0.01',
            'payment_mode' => 'required|in:cash,upi,online',
            'expense_date' => 'required|date',
        ]);

        $expense = BillingExpense::create([
            'user_id' => $request->user()->id,
            'category' => $request->category,
            'description' => $request->description,
            'amount' => $request->amount,
            'payment_mode' => $request->payment_mode,
            'expense_date' => $request->expense_date,
        ]);

        return response()->json(['success' => true, 'expense' => $expense]);
    }

    public function updateExpense(Request $request, $id)
    {
        $expense = BillingExpense::where('user_id', $request->user()->id)->findOrFail($id);
        $expense->update($request->only(['category', 'description', 'amount', 'payment_mode', 'expense_date']));
        return response()->json(['success' => true]);
    }

    public function destroyExpense(Request $request, $id)
    {
        BillingExpense::where('user_id', $request->user()->id)->findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    // Monthly Fixed Expenses
    public function storeMonthlyExpense(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'expense_type' => 'required|in:monthly,one_time',
        ]);

        BillingMonthlyExpense::create([
            'user_id' => $request->user()->id,
            'category' => $request->category,
            'amount' => $request->amount,
            'expense_type' => $request->expense_type,
        ]);

        return back()->with('success', 'Monthly expense added!');
    }

    public function updateMonthlyExpense(Request $request, $id)
    {
        $exp = BillingMonthlyExpense::where('user_id', $request->user()->id)->findOrFail($id);
        $exp->update($request->only(['category', 'amount', 'expense_type', 'is_active']));
        return response()->json(['success' => true]);
    }

    public function destroyMonthlyExpense(Request $request, $id)
    {
        BillingMonthlyExpense::where('user_id', $request->user()->id)->findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    // ==================== CUSTOMERS ====================

    public function customers(Request $request)
    {
        $userId = $request->user()->id;
        $query = BillingCustomer::where('user_id', $userId);

        if ($request->search) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")->orWhere('mobile', 'like', "%$s%");
            });
        }

        $customers = $query->orderBy('updated_at', 'desc')->paginate(25);

        $stats = [
            'total' => BillingCustomer::where('user_id', $userId)->count(),
            'revenue' => BillingCustomer::where('user_id', $userId)->sum('total_spent'),
            'visits' => BillingCustomer::where('user_id', $userId)->sum('total_visits'),
        ];

        return view('billing.customers', compact('customers', 'stats'));
    }

    public function storeCustomer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'nullable|string|max:15',
        ]);

        $customer = BillingCustomer::create([
            'user_id' => $request->user()->id,
            'name' => $request->name,
            'mobile' => $request->mobile,
            'aadhaar_last_four' => $request->aadhaar_last_four,
            'address' => $request->address,
            'notes' => $request->notes,
        ]);

        return response()->json(['success' => true, 'customer' => $customer]);
    }

    public function showCustomer(Request $request, $id)
    {
        $customer = BillingCustomer::where('user_id', $request->user()->id)->findOrFail($id);
        $sales = BillingSale::where('user_id', $request->user()->id)
            ->where('customer_id', $id)->active()
            ->with('items')
            ->orderBy('sale_date', 'desc')
            ->paginate(20);

        return view('billing.customer-detail', compact('customer', 'sales'));
    }

    public function destroyCustomer(Request $request, $id)
    {
        BillingCustomer::where('user_id', $request->user()->id)->findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    // ==================== DAILY BOOK ====================

    public function dailyBook(Request $request)
    {
        $userId = $request->user()->id;
        $today = Carbon::today();
        $profile = $request->user()->profile;

        $dailyBook = BillingDailyBook::where('user_id', $userId)->where('business_date', $today)->first();

        // Today's data
        $todaySales = BillingSale::where('user_id', $userId)->active()->where('sale_date', $today);
        $cashSales = (clone $todaySales)->whereIn('payment_mode', ['cash', 'split'])->sum('cash_amount');
        $onlineSales = (clone $todaySales)->whereIn('payment_mode', ['upi', 'online', 'split'])->sum('online_amount');
        $unpaidSales = (clone $todaySales)->where('payment_status', '!=', 'paid')->sum('due_amount');
        $totalSalesAmount = (clone $todaySales)->sum('total_amount');
        $todayExpenses = BillingExpense::where('user_id', $userId)->where('expense_date', $today)->sum('amount');
        $cashExpenses = BillingExpense::where('user_id', $userId)->where('expense_date', $today)->where('payment_mode', 'cash')->sum('amount');

        $adjustments = $dailyBook ? BillingCashAdjustment::where('daily_book_id', $dailyBook->id)->get() : collect();
        $cashAdded = $adjustments->where('type', 'add')->sum('amount');
        $cashRemoved = $adjustments->where('type', 'remove')->sum('amount');

        // Kiosk summary
        $kioskNet = 0;
        if ($profile->kiosk_enabled) {
            $kioskToday = BillingKioskTransaction::where('user_id', $userId)->where('transaction_date', $today);
            $kioskNet = (clone $kioskToday)->sum('manual_commission');
        }

        $expectedCash = ($dailyBook->opening_cash ?? 0) + $cashSales + $cashAdded - $cashRemoved + $kioskNet - $cashExpenses;

        $sales = BillingSale::where('user_id', $userId)->active()->where('sale_date', $today)
            ->with('items')->orderBy('created_at', 'desc')->get();
        $deletedSales = BillingSale::where('user_id', $userId)->deleted()->where('sale_date', $today)->get();

        // Previous day closing for suggestion
        $prevBook = BillingDailyBook::where('user_id', $userId)
            ->where('business_date', '<', $today)->orderBy('business_date', 'desc')->first();

        return view('billing.daily-book', compact(
            'dailyBook', 'cashSales', 'onlineSales', 'unpaidSales', 'totalSalesAmount',
            'todayExpenses', 'cashExpenses', 'adjustments', 'cashAdded', 'cashRemoved',
            'expectedCash', 'sales', 'deletedSales', 'prevBook', 'kioskNet', 'profile'
        ));
    }

    public function startDay(Request $request)
    {
        $request->validate(['opening_cash' => 'required|numeric|min:0']);

        $book = BillingDailyBook::create([
            'user_id' => $request->user()->id,
            'business_date' => Carbon::today(),
            'opening_cash' => $request->opening_cash,
            'status' => 'open',
        ]);

        return response()->json(['success' => true, 'book' => $book]);
    }

    public function closeDay(Request $request)
    {
        $book = BillingDailyBook::where('user_id', $request->user()->id)
            ->where('business_date', Carbon::today())->firstOrFail();

        $book->update([
            'closing_cash' => $request->closing_cash ?? 0,
            'expected_cash' => $request->expected_cash ?? 0,
            'difference' => ($request->closing_cash ?? 0) - ($request->expected_cash ?? 0),
            'status' => 'closed',
            'close_version' => $book->close_version + 1,
            'closing_notes' => $request->closing_notes,
        ]);

        return response()->json(['success' => true]);
    }

    public function reopenDay(Request $request)
    {
        $book = BillingDailyBook::where('user_id', $request->user()->id)
            ->where('business_date', Carbon::today())->firstOrFail();

        $book->update(['status' => 'open', 'closing_cash' => null]);

        return response()->json(['success' => true]);
    }

    public function addCashAdjustment(Request $request)
    {
        $request->validate([
            'type' => 'required|in:add,remove',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $book = BillingDailyBook::where('user_id', $request->user()->id)
            ->where('business_date', Carbon::today())->firstOrFail();

        BillingCashAdjustment::create([
            'user_id' => $request->user()->id,
            'daily_book_id' => $book->id,
            'type' => $request->type,
            'amount' => $request->amount,
            'reason' => $request->reason,
        ]);

        return response()->json(['success' => true]);
    }

    // ==================== KIOSK BOOK ====================

    public function kioskBook(Request $request)
    {
        $userId = $request->user()->id;
        $query = BillingKioskTransaction::where('user_id', $userId);

        if ($request->search) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('customer_name', 'like', "%$s%")
                  ->orWhere('customer_mobile', 'like', "%$s%")
                  ->orWhere('aadhaar_last_four', 'like', "%$s%");
            });
        }
        if ($request->type) {
            $query->where('transaction_type', $request->type);
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->paginate(25);

        $summary = [
            'withdrawals' => BillingKioskTransaction::where('user_id', $userId)->where('transaction_type', 'withdraw')->sum('amount'),
            'deposits' => BillingKioskTransaction::where('user_id', $userId)->where('transaction_type', 'deposit')->sum('amount'),
            'cash_commission' => BillingKioskTransaction::where('user_id', $userId)->sum('manual_commission'),
            'portal_commission' => BillingKioskTransaction::where('user_id', $userId)->sum('portal_commission'),
        ];

        return view('billing.kiosk-book', compact('transactions', 'summary'));
    }

    public function storeKioskTransaction(Request $request)
    {
        $request->validate([
            'transaction_type' => 'required|in:withdraw,deposit,balance,mini_statement',
            'amount' => 'nullable|numeric|min:0',
            'transaction_date' => 'required|date',
        ]);

        $transaction = BillingKioskTransaction::create([
            'user_id' => $request->user()->id,
            'transaction_type' => $request->transaction_type,
            'customer_name' => $request->customer_name,
            'customer_mobile' => $request->customer_mobile,
            'aadhaar_last_four' => $request->aadhaar_last_four,
            'bank_name' => $request->bank_name,
            'amount' => $request->amount ?? 0,
            'manual_commission' => $request->manual_commission ?? 0,
            'portal_commission' => $request->portal_commission ?? 0,
            'transaction_date' => $request->transaction_date,
            'remarks' => $request->remarks,
        ]);

        return response()->json(['success' => true, 'transaction' => $transaction]);
    }

    public function destroyKioskTransaction(Request $request, $id)
    {
        BillingKioskTransaction::where('user_id', $request->user()->id)->findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    // ==================== REPORTS ====================

    public function reports(Request $request)
    {
        $userId = $request->user()->id;
        $range = $request->get('range', 'month');
        $today = Carbon::today();

        switch ($range) {
            case 'week':
                $startDate = $today->copy()->subWeek();
                break;
            case 'quarter':
                $startDate = $today->copy()->subMonths(3);
                break;
            case 'year':
                $startDate = $today->copy()->subYear();
                break;
            default:
                $startDate = $today->copy()->startOfMonth();
        }
        $endDate = $today;
        $daysInRange = $startDate->diffInDays($endDate) + 1;

        $totalSales = BillingSale::where('user_id', $userId)->active()
            ->whereBetween('sale_date', [$startDate, $endDate])->sum('total_amount');
        $dailyExpenses = BillingExpense::where('user_id', $userId)
            ->whereBetween('expense_date', [$startDate, $endDate])->sum('amount');
        $totalMonthlyFixed = BillingMonthlyExpense::where('user_id', $userId)
            ->where('is_active', true)->where('expense_type', 'monthly')->sum('amount');
        $proratedMonthlyFixed = round(($totalMonthlyFixed / 30) * $daysInRange, 2);
        $totalExpenses = $dailyExpenses + $proratedMonthlyFixed;
        $netProfit = $totalSales - $totalExpenses;

        // Daily breakdown for chart
        $dailyData = [];
        for ($d = $startDate->copy(); $d->lte($endDate); $d->addDay()) {
            $ds = $d->toDateString();
            $dailyData[] = [
                'date' => $d->format('d M'),
                'sales' => (float) BillingSale::where('user_id', $userId)->active()->where('sale_date', $ds)->sum('total_amount'),
                'expenses' => (float) BillingExpense::where('user_id', $userId)->where('expense_date', $ds)->sum('amount'),
            ];
        }

        // Category breakdown
        $expensesByCategory = BillingExpense::where('user_id', $userId)
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')->orderByDesc('total')->get();

        return view('billing.reports', compact(
            'totalSales', 'dailyExpenses', 'proratedMonthlyFixed', 'totalExpenses', 'netProfit',
            'dailyData', 'expensesByCategory', 'range', 'startDate', 'endDate', 'daysInRange'
        ));
    }

    // ==================== SERVICES ====================

    public function services(Request $request)
    {
        $userId = $request->user()->id;

        // Auto-seed default CSC services if no system defaults with name_mr exist
        $hasDefaults = BillingService::where('user_id', $userId)
            ->where('is_system_default', true)
            ->whereNotNull('name_mr')
            ->where('name_mr', '!=', '')
            ->exists();

        if (!$hasDefaults) {
            $this->seedDefaultServices($userId);
        }

        $services = BillingService::where('user_id', $userId)->orderBy('category')->orderBy('display_order')->get();
        $categories = $services->pluck('category')->unique()->values();

        return view('billing.services', compact('services', 'categories'));
    }

    private function seedDefaultServices(int $userId): void
    {
        $cscServices = config('csc_services', []);
        $order = BillingService::where('user_id', $userId)->max('display_order') ?? 0;

        foreach ($cscServices as $category => $items) {
            foreach ($items as $item) {
                BillingService::updateOrCreate(
                    ['user_id' => $userId, 'name' => $item['name']],
                    [
                        'name_mr' => $item['name_mr'],
                        'category' => $category,
                        'default_price' => $item['default_price'],
                        'cost_price' => $item['cost_price'],
                        'is_active' => true,
                        'is_system_default' => true,
                        'display_order' => ++$order,
                    ]
                );
            }
        }
    }

    public function storeService(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'default_price' => 'required|numeric|min:0',
        ]);

        $maxOrder = BillingService::where('user_id', $request->user()->id)->max('display_order') ?? 0;

        BillingService::create([
            'user_id' => $request->user()->id,
            'name' => $request->name,
            'name_mr' => $request->name_mr ?? '',
            'category' => $request->category ?? 'General',
            'default_price' => $request->default_price,
            'cost_price' => $request->cost_price ?? 0,
            'display_order' => $maxOrder + 1,
        ]);

        return response()->json(['success' => true]);
    }

    public function updateService(Request $request, $id)
    {
        $svc = BillingService::where('user_id', $request->user()->id)->findOrFail($id);
        $svc->update($request->only(['name', 'name_mr', 'category', 'default_price', 'cost_price', 'is_active', 'display_order']));
        return response()->json(['success' => true]);
    }

    public function destroyService(Request $request, $id)
    {
        $svc = BillingService::where('user_id', $request->user()->id)->findOrFail($id);
        $svc->delete();
        return response()->json(['success' => true]);
    }

    public function csvUploadServices(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('csv_file');
        $rows = array_map('str_getcsv', file($file->getRealPath()));
        $imported = 0;
        $skipped = 0;

        foreach ($rows as $row) {
            if (count($row) < 5) { $skipped++; continue; }
            $name = trim($row[0]);
            if (!$name || strtolower($name) === 'name') { $skipped++; continue; }

            BillingService::updateOrCreate(
                ['user_id' => $request->user()->id, 'name' => $name],
                [
                    'name_mr' => trim($row[1]) ?: null,
                    'category' => trim($row[2]) ?: 'Other',
                    'cost_price' => (float) trim($row[3]),
                    'default_price' => (float) trim($row[4]),
                    'is_active' => true,
                ]
            );
            $imported++;
        }

        return redirect()->back()->with('success', "{$imported} services imported, {$skipped} skipped.");
    }
}
