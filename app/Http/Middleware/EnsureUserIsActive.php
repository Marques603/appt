<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsActive
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->status === 0) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Sua conta está inativa. Aguarde aprovação do administrador.',
            ]);
        }

        return $next($request);
    }
}
