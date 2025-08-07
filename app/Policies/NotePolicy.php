<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Note;


class NotePolicy
{
    public function admin(User $user)
    {
        return $user->roles()
            ->where('module', 'notas')
            ->where('name', ['admin', 'edit', 'view'])
            ->exists();
    }

    public function edit(User $user)
    {
        return $user->roles()
            ->where('module', 'notas')
            ->whereIn('name', ['admin', 'edit'])
            ->exists();
    }

    public function view(User $user)
    {
        return $user->roles()
            ->where('module', 'notas')
            ->whereIn('name', ['admin', 'edit', 'view'])
            ->exists();
    }

    public function delete(User $user)
    {
        return $user->roles()
            ->where('module', 'notas')
            ->where('name', 'admin')
            ->exists();
    }
    public function aprovar(User $user, Note $note)
    {
            return $user->roles()
            ->where('module', 'notas')
            ->whereIn('name', ['admin', 'diretor'])
            ->exists();
    }
        public function reprovar(User $user, Note $note)
    {
            return $user->roles()
            ->where('module', 'notas')
            ->whereIn('name', ['admin', 'diretor'])
            ->exists();
    }   
    public function lancar(User $user, Note $note)
    {
        return $user->roles()
            ->where('module', 'notas')
            ->whereIn('name', ['admin', 'diretor', 'lancamentos'])
            ->exists();
    }
    public function pagar(User $user, Note $note)
    {
        return $user->roles()
            ->where('module', 'notas')
            ->whereIn('name', ['admin', 'diretor', 'lancamentos', 'pagamentos'])
            ->exists();
    }
}
