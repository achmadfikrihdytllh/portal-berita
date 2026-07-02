<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function subscribe(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email', 'max:255', 'unique:newsletter_subscribers,email'],
        ], [
            'email.unique' => 'Email ini sudah terdaftar sebagai pelanggan newsletter.',
        ]);

        NewsletterSubscriber::create([
            'email' => $request->input('email'),
            'token' => Str::random(40),
        ]);

        return back()->with('success', 'Terima kasih telah berlangganan! Silakan cek email untuk verifikasi.');
    }
}
