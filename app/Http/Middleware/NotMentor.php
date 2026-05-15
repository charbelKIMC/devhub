<?php
namespace App\Http\Middleware;
use Closure; use Illuminate\Http\Request; use Illuminate\Support\Facades\Auth;
 
class NotMentor {
    public function handle(Request $request, Closure $next) {
        if (Auth::check() && Auth::user()->role === 'mentor') {
            return redirect()->route('mentor.espace');
        }
        return $next($request);
    }
}