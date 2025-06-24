<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Models\UserLog;
use Illuminate\Support\Facades\Request;

class LogUserLogin
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        $user = $event->user;

        // Atualiza o campo last_login_at
        $user->last_login_at = now();
        $user->save();

        // Cria o registro na tabela users_logs
        UserLog::create([
            'user_id' => $user->id,
            'ip_address' => Request::ip(),
            'user_agent' => Request::header('User-Agent'),
        ]);
    }
}
