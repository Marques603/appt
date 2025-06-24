<?php

namespace App\Policies;

use App\Models\Menu;
use App\Models\User;

class MenuPolicy
{
    public function view(User $user, Menu $menu)
    {
        return $user->menus->contains($menu->id);
    }
}
