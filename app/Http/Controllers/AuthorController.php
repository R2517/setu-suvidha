<?php

namespace App\Http\Controllers;

use App\Models\ContactRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AuthorController extends Controller
{
    public function index()
    {
        return view('pages.author');
    }

    public function submitRequest(Request $request)
    {
        // Rate limit: max 3 requests per IP per hour
        $key = 'contact-request:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            return back()->with('error', "Too many requests. Please try again in {$seconds} seconds.");
        }

        $request->validate([
            'name'       => 'required|string|max:100',
            'email'      => 'required|email|max:150',
            'subject'    => 'required|string|max:200',
            'message'    => ['required', 'string', 'max:2000', function ($attr, $value, $fail) {
                // Block URLs/links in message
                if (preg_match('/(https?:\/\/|www\.|\.com|\.in|\.org|\.net|\.co|\.io|bit\.ly|tinyurl)/i', $value)) {
                    $fail('Links/URLs are not allowed in the message.');
                }
            }],
            'attachment'  => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ], [
            'message.max'      => 'Message must be under 2000 characters.',
            'attachment.mimes'  => 'Only PDF and image files (JPG, PNG) are allowed.',
            'attachment.max'    => 'File size must be under 2MB.',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('contact-attachments', 'public');
        }

        ContactRequest::create([
            'name'            => $request->name,
            'email'           => $request->email,
            'subject'         => $request->subject,
            'message'         => $request->message,
            'attachment_path'  => $attachmentPath,
            'ip_address'      => $request->ip(),
        ]);

        RateLimiter::hit($key, 3600);

        return back()->with('success', 'Your request has been submitted successfully! We will get back to you soon.');
    }
}
