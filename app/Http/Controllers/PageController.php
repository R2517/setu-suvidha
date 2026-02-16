<?php

namespace App\Http\Controllers;

use App\Models\FormSubmission;

class PageController extends Controller
{
    public function home() { return view('pages.home'); }
    public function about() { return view('pages.about'); }
    public function contact() { return view('pages.contact'); }
    public function services() { return view('pages.services'); }
    public function howItWorks() { return view('pages.how-it-works'); }
    public function benefits() { return view('pages.benefits'); }
    public function faq() { return view('pages.faq'); }
    public function terms() { return view('pages.terms'); }
    public function privacy() { return view('pages.privacy'); }
    public function refund() { return view('pages.refund'); }
    public function disclaimer() { return view('pages.disclaimer'); }
    public function bandkamInfo() { return view('pages.bandkam-info'); }
    public function billing()
    {
        $user = auth()->user();
        $submissions = FormSubmission::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')->paginate(20);
        return view('dashboard.billing', compact('user', 'submissions'));
    }
}
