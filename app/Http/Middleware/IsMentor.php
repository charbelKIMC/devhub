<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsMentor
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->isMentor()) {
            abort(403, 'Accès refusé.');
        }

        $contratSigne = \App\Models\ContratMentor::where('mentor_id', Auth::id())
            ->where('statut', 'signé')
            ->exists();

        if (!$contratSigne) {
            return redirect()->route('mentor.contrat.show');
        }

        return $next($request);
    }
}