<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Document;

class DocumentPolicy
{
    public function create(User $user)
    {
        return $user->roles()->where('module', 'qualidade')->where('name', 'admin')->exists();
    }

    public function edit(User $user)
    {
        return $user->roles()
            ->where('module', 'qualidade')
            ->whereIn('name', ['admin', 'edit'])
            ->exists();
    }

    public function view(User $user)
    {
        return $user->roles()
            ->where('module', 'qualidade')
            ->whereIn('name', ['admin', 'edit', 'view'])
            ->exists();
    }

    public function delete(User $user)
    {
        return $user->roles()->where('module', 'qualidade')->where('name', 'admin')->exists();
    }
}
