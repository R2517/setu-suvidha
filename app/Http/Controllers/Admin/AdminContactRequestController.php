<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactRequest;
use Illuminate\Http\Request;

class AdminContactRequestController extends Controller
{
    public function index()
    {
        $requests = ContactRequest::orderByDesc('created_at')->paginate(25);
        $newCount = ContactRequest::where('status', 'new')->count();
        return view('admin.contact-requests', compact('requests', 'newCount'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:new,read,replied']);
        $cr = ContactRequest::findOrFail($id);
        $cr->update(['status' => $request->status]);
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $cr = ContactRequest::findOrFail($id);
        if ($cr->attachment_path) {
            \Storage::disk('public')->delete($cr->attachment_path);
        }
        $cr->delete();
        return response()->json(['success' => true]);
    }
}
