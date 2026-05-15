<?php

namespace App\Http\Controllers;

use App\Models\Favori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriController extends Controller
{
    public function toggle(Request $request, $publication_id)
    {
        $userId = Auth::id();

        $favori = Favori::where('utilisateur_id', $userId)
                        ->where('publication_id', $publication_id)
                        ->first();

        if ($favori) {
            Favori::where('utilisateur_id', $userId)
                ->where('publication_id', $publication_id)
                ->delete();
            $saved = false;
        } else {
            Favori::create([
                'utilisateur_id' => $userId,
                'publication_id' => $publication_id,
                'date_ajout'     => now(),
            ]);
            $saved = true;
        }

        return response()->json(['saved' => $saved]);
    }
}