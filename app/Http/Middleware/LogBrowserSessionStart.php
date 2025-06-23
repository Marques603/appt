<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Models\UserLog;

class LogBrowserSessionStart
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            // Se não existe o cookie que indica sessão ativa no navegador
            if (!$request->hasCookie('browser_session')) {
                // Grava o log
                UserLog::create([
                    'user_id' => Auth::id(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->header('User-Agent'),
                ]);

                // Cria cookie temporário (duração 0 = expira ao fechar navegador)
                Cookie::queue('browser_session', uniqid(), 0);
            }
        }

        return $next($request);
    }
}
