<?php

namespace App\Policies;

use App\Models\User;


class ConciergePolicy
{
    public function create(User $user)
    {
        return $user->roles()
            ->where('module', 'portaria')
            ->where('name', 'admin')
            ->exists();
    }

    public function edit(User $user)
    {
        return $user->roles()
            ->where('module', 'portaria')
            ->whereIn('name', ['admin', 'edit'])
            ->exists();
    }

    public function view(User $user)
    {
        return $user->roles()
            ->where('module', 'portaria')
            ->whereIn('name', ['admin', 'edit', 'view'])
            ->exists();
    }

    public function delete(User $user)
    {
        return $user->roles()
            ->where('module', 'portaria')
            ->where('name', 'admin')
            ->exists();
    }
}
