<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;

class ContactController extends Controller
{
    public function index()
    {
        return view('pages.contact');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'     => 'required|string|max:100',
            'email'   => 'required|email|max:255',
            'sujet'   => 'required|string|max:200',
            'message' => 'required|string|min:10|max:3000',
        ]);

        ContactMessage::create($request->only('nom', 'email', 'sujet', 'message'));

        return back()->with('contact_success', 'Votre message a bien été envoyé ! Nous vous répondrons dans les plus brefs délais.');
    }
}
