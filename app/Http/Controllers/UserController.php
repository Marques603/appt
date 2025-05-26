<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sector;
use App\Models\Menu;
use App\Models\Role;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index(Request $request)
{
    if (!Gate::allows('view', Menu::find(1))) {
        return redirect()->route('sector.index')->with('status', 'Este menu não está liberado para o seu perfil.');
    }

    $users = User::query();

    $users->when($request->input('search'), function ($query, $keyword) {
        $query->where(function ($q) use ($keyword) {
            $q->where('name', 'like', '%' . $keyword . '%')
                ->orWhere('email', 'like', '%' . $keyword . '%');
        });
    });

    // Adiciona a ordenação alfabética por nome
    $users = $users->with('positions')->orderBy('name', 'asc')->paginate(10);

    return view('users.index', compact('users'));
}


    public function create()
    {
        $sector = Sector::all();
        $roles = Role::all();
        return view('users.create', compact('sector', 'roles'));
    }

    public function store(Request $request)
    {
        $input = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        if ($avatarPath = $this->handleAvatarUpload($request)) {
            $input['avatar'] = $avatarPath;
        }

        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

        return redirect()->route('users.index')->with('status', 'Usuário adicionado com sucesso.');
    }

    public function edit(User $user)
    {
        Gate::authorize('edit', User::class);

        $user->load('menus', 'roles');
        $sector = Sector::where('status', 1)->get();
        $menus = Menu::all();
        $roles = Role::all();

        return view('users.edit', compact('user', 'sector',  'menus', 'roles'));
    }

   

public function update(Request $request, User $user)
{
    $input = $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'password' => 'nullable|min:8',
        'type' => 'nullable|string|max:1',
        'registration' => 'nullable|string|max:255',
        'admission' => 'nullable|string', // Altere para string pois virá como "27-07-2025"
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'status' => 'required|boolean',
        'roles' => 'nullable|array',
        'roles.*' => 'exists:roles,id',
    ]);

    // Converte a data para o formato aceito pelo banco (Y-m-d)
    if (!empty($input['admission'])) {
        try {
            $input['admission'] = Carbon::createFromFormat('d-m-Y', $input['admission'])->format('Y-m-d');
        } catch (\Exception $e) {
            // Se der erro, você pode invalidar manualmente:
            return back()->withErrors(['admission' => 'Data de admissão inválida.']);
        }
    }

    if ($request->filled('password')) {
        $input['password'] = Hash::make($request->password);
    } else {
        unset($input['password']);
    }

    if ($avatarPath = $this->handleAvatarUpload($request)) {
        $input['avatar'] = $avatarPath;
    }

    $user->update($input);

    if ($request->has('roles')) {
        $user->roles()->sync($request->roles);
    }

    return redirect()->route('users.edit', $user)->with('success', 'Usuário atualizado com sucesso.');
}


    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('status', 'Usuário removido com sucesso.');
    }

    public function updatesector(Request $request, User $user)
    {
        $validated = $request->validate([
            'sector' => 'nullable|array',
            'sector.*' => 'exists:sector,id',
        ]);

        $user->sector()->sync($validated['sector'] ?? []);
        return redirect()->route('users.edit', $user)->with('success', 'Setores atualizados com sucesso.');
    }


    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'status' => 'required|in:0,1',
        ]);

        $user->status = (int) $request->status;
        $user->save();

        return redirect()->route('users.edit', $user)->with('success', 'Status do usuário atualizado com sucesso!');
    }

    public function updateProfile(Request $request, User $user)
{
    $input = $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'password' => 'nullable|min:8|confirmed',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'type' => 'nullable|string|max:1',
        'registration' => 'nullable|string|max:255',
        'admission' => 'nullable|date',
    ]);

    if ($request->filled('password')) {
        $input['password'] = bcrypt($request->password);
    } else {
        unset($input['password']);
    }

    if ($avatarPath = $this->handleAvatarUpload($request)) {
        $input['avatar'] = $avatarPath;
    }

    // Atualiza os campos no usuário
    $user->update($input);

    return redirect()->route('users.edit', $user)->with('success', 'Usuário atualizado com sucesso.');
}


    public function updateRoles(Request $request, User $user)
    {
        $input = $request->validate([
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user->roles()->sync($input['roles'] ?? []);
        return redirect()->route('users.edit', $user)->with('success', 'Funções atualizadas com sucesso.');
    }

    public function updateMenus(Request $request, User $user)
    {
        $input = $request->validate([
            'menus' => 'nullable|array',
            'menus.*' => 'exists:menus,id',
        ]);

        $user->menus()->sync($input['menus'] ?? []);
        return redirect()->route('users.edit', $user->id)->with('success', 'Menus atualizados com sucesso!');
    }

    private function handleAvatarUpload(Request $request)
    {
        return $request->hasFile('avatar')
            ? $request->file('avatar')->store('images/profiles', 'public')
            : null;
    }
    public function updatePassword(Request $request, User $user)
{
    $request->validate([
        'password' => 'required|string|min:8|confirmed',
    ]);

    $user->password = Hash::make($request->password);
    $user->save();

    return redirect()->back()->with('success', 'Senha atualizada com sucesso!');
}

    
}
