<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HelpdeskTicket;
use App\Models\HelpdeskMessage;

class HelpdeskController extends Controller
{
    public function index()
    {
        $tickets = HelpdeskTicket::with('user')->latest()->paginate(20);
        return view('admin.helpdesk.index', compact('tickets'));
    }

    public function show(HelpdeskTicket $ticket)
    {
        $ticket->load('user', 'messages');
        return view('admin.helpdesk.show', compact('ticket'));
    }

    public function reply(Request $request, HelpdeskTicket $ticket)
    {
        $request->validate([
            'message' => 'required_without:status|string|nullable',
            'attachment' => 'nullable|file|max:5120',
            'status' => 'nullable|in:open,resolved,closed'
        ]);

        if ($request->has('status') && $request->status) {
            $ticket->update(['status' => $request->status]);
        }

        if ($request->filled('message') || $request->hasFile('attachment')) {
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('helpdesk', 'public');
            }

            HelpdeskMessage::create([
                'helpdesk_ticket_id' => $ticket->id,
                'sender_type' => 'admin',
                'sender_id' => auth()->id(),
                'message' => $request->message ?? '',
                'attachment_path' => $attachmentPath,
            ]);
            
            if ($ticket->status === 'closed') {
                $ticket->update(['status' => 'open']);
            }
        }

        return back()->with('success', 'Ticket updated successfully.');
    }
}
