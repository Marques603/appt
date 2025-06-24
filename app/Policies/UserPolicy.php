<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function destroy(User $user)
    {
        return $user->roles()->where('module', 'tecnologia')->where('name', 'admin')->exists();
    }

    public function edit(User $user)
    {
        return $user->roles()->where('module', 'tecnologia')->where('name', 'admin')->exists();
    }

    public function create(User $user)
    {
        // Permitir apenas usuários com o papel "admin" criar novos usuários
        return $user->roles()->where('module', 'tecnologia')->where('name', 'admin')->exists();
    }
}