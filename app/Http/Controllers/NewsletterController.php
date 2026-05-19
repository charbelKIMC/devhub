<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewsletterAbonne;

class NewsletterController extends Controller
{
    public function abonner(Request $request)
    {
        $request->validate(['email' => 'required|email|max:255']);

        if (NewsletterAbonne::where('email', $request->email)->exists()) {
            return back()->with('newsletter_success', 'Vous êtes déjà abonné à la newsletter DevHub !');
        }

        NewsletterAbonne::create(['email' => $request->email]);

        return back()->with('newsletter_success', 'Merci ! Vous êtes maintenant abonné à la newsletter DevHub.');
    }
}
