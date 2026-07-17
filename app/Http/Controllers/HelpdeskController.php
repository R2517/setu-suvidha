<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HelpdeskTicket;
use App\Models\HelpdeskMessage;

class HelpdeskController extends Controller
{
    public function index()
    {
        $tickets = HelpdeskTicket::where('user_id', auth()->id())->latest()->get();
        return view('helpdesk.index', compact('tickets'));
    }

    public function show(HelpdeskTicket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }
        $ticket->load('messages');
        return view('helpdesk.show', compact('ticket'));
    }

    public function submit(Request $request)
    {
        $request->validate([
            'type' => 'required|in:suggestion,grievance,help',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:5120'
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('helpdesk', 'public');
        }

        $ticket = HelpdeskTicket::create([
            'user_id' => auth()->id(),
            'type' => $request->type,
            'subject' => $request->subject,
            'message' => $request->message,
            'attachment_path' => $attachmentPath,
            'status' => 'open'
        ]);

        return response()->json(['success' => true, 'ticket_id' => $ticket->id]);
    }

    public function reply(Request $request, HelpdeskTicket $ticket)
    {
        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:5120'
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('helpdesk', 'public');
        }

        HelpdeskMessage::create([
            'helpdesk_ticket_id' => $ticket->id,
            'sender_type' => 'user',
            'sender_id' => auth()->id(),
            'message' => $request->message,
            'attachment_path' => $attachmentPath,
        ]);

        return back()->with('success', 'Reply sent successfully.');
    }
}
