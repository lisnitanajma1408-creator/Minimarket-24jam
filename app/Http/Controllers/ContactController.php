<?php

namespace App\Http\Controllers;

use App\Models\StoreSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMessage;
use App\Models\User;

class ContactController extends Controller
{
    public function index()
    {
        $store = StoreSetting::first();
        return view('kontak', compact('store'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $adminEmails = User::where('role', 'admin')->pluck('email');
        foreach ($adminEmails as $email) {
            Mail::to($email)->send(new ContactMessage($validated));
        }

        return redirect()->route('kontak')->with('success', 'Pesan kamu berhasil dikirim! Kami akan segera merespon.');
    }
}