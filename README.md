Clone Repositório
```sh
git clone https://github.com/wesleyfernandocabrera/appz.git


```sh
cd appz
```

Suba os containers do projeto
```sh
docker-compose up -d
```


Crie o Arquivo .env
```sh
cp .env.example .env
```

Acesse o container app
```sh
docker-compose exec app bash
```


Instale as dependências do projeto
```sh
composer install
```

Gere a key do projeto Laravel
```sh
php artisan key:generate
```
Rodar as migrations
```sh
php artisan migrate

Rodar para criar storage
```sh
php artisan storage:link

Pare os containers se acaso
```sh
docker compose down

Apague os dados do MySQL se acaso der erro no php artisan migrate
```sh
rm -rf ./.docker/mysql/dbdata/*

LINK APP
[http://localhost:8000](http://localhost:8000)

# appz

criar novo
echo "# appz" >> README.md
git init
git add README.md
git commit -m "first commit"
git branch -M main
git remote add origin https://github.com/wesleyfernandocabrera/appz.git
git push -u origin main

usar existente

git remote add origin https://github.com/wesleyfernandocabrera/appz.git
git branch -M main
git push -u origin main

#finalizar



talvez tem que rodar isso 

# Pare os containers
docker compose down

# Apague os dados do MySQL
rm -rf ./.docker/mysql/dbdata/*

# Suba novamente
docker compose up -d


rodar isso quando rodar um fresh migrate 

INSERT INTO appz.roles (
    id,
    name,
    created_at,
    updated_at
)
VALUES (
    1,
    'admin',
    NOW(),
    NOW()
);

INSERT INTO appz.roles (
    id,
    name,
    created_at,
    updated_at
)
VALUES (
    2,
    'edit',
    NOW(),
    NOW()
);

INSERT INTO appz.roles (
    id,
    name,
    created_at,
    updated_at
)
VALUES (
    3,
    'view',
    NOW(),
    NOW()
);
INSERT INTO menu_user (user_id, menu_id) VALUES (1, 1);

INSERT INTO menus (
    id,
    name,
    created_at,
    updated_at
)
VALUES (
    1,
    'tecnologia',
    NOW(),
    NOW()
);

INSERT INTO menus (
    id,
    name,
    created_at,
    updated_at
)
VALUES (
    2,
    'qualidade',
    NOW(),
    NOW()
);


 @can('view', App\Models\Menu::find(1)) 

 @endcan


d
 <?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Sector;
use App\Models\Company;
use App\Models\Menu;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {

 
        Gate::authorize('view', Menu::find(1)); // Tecnologia
        Gate::authorize('view', Menu::find(2)); // Qualidade
        Gate::authorize('view', Menu::find(3)); // RH

        
        $users = User::query();

        $users->when($request->input('search'), function ($query, $keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%')
                    ->orWhere('email', 'like', '%' . $keyword . '%');
            });
        });

        $users = $users->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $sector = Sector::all();
        $roles = Role::all();  // Carregando todos os papéis
        return view('users.create', compact('sector', 'roles'));
    }

    public function store(Request $request)
    {
        $input = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'roles' => 'nullable|array',  // Validando os papéis
        ]);

        if ($request->hasFile('avatar')) {
            $input['avatar'] = $request->file('avatar')->store('images/profiles', 'public');
        }

        $input['password'] = bcrypt($input['password']);

        $user = User::create($input);

        // Sincronizando os papéis
        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

        return redirect()->route('users.index')->with('status', 'Usuário adicionado com sucesso.');
    }

    public function edit(User $user)
    {

        Gate::authorize('edit', User::class); // Só admins
        

        $user->load('menus', 'roles'); // Carregando menus e papéis

        $company = Company::where('status', 1)->get();
        $sector = Sector::where('status', 1)->get();
        $menus = Menu::all();  // Carregando menus
        $roles = Role::all();  // Carregando papéis

        return view('users.edit', compact('user', 'sector', 'company', 'menus', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        
        $input = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|boolean',
            'roles' => 'nullable|array',  // Validando os papéis
        ]);

        if ($request->filled('password')) {
            $input['password'] = Hash::make($input['password']);
        } else {
            unset($input['password']);
        }

        if ($request->hasFile('avatar')) {
            $input['avatar'] = $request->file('avatar')->store('images/profiles', 'public');
        }

        $user->update($input);

        // Sincronizando os papéis
        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

        return redirect()->route('users.index')->with('status', 'Usuário atualizado com sucesso.');
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

        return redirect()->route('users.index')
                         ->with('status', 'Setores atualizados com sucesso.');
    }

    public function updatecompany(Request $request, User $user)
    {
        
        $request->validate([
            'company' => 'array',
            'company.*' => 'exists:company,id',
        ]);

        $user->company()->sync($request->company ?? []);

        return redirect()->route('users.index')
                         ->with('status', 'Empresas atualizadas com sucesso!');
    }

    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'status' => 'required|in:0,1',
        ]);

        $user->status = (int) $request->status;
        $user->save();

        return redirect()->route('users.index')->with('status', 'Status do usuário atualizado com sucesso!');
    }

    public function updateProfile(Request $request, User $user)
    {
       
        $input = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->filled('password')) {
            $input['password'] = bcrypt($input['password']);
        } else {
            unset($input['password']);
        }

        if ($request->hasFile('avatar')) {
            $input['avatar'] = $request->file('avatar')->store('images/profiles', 'public');
        }

        $user->update($input);

        return redirect()->route('users.index')->with('status', 'Usuário atualizado com sucesso.');
    }

    
    public function updateRoles(Request $request, User $user)
    {
        
        $input = $request->validate([
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,id',  // Validando os IDs de papéis
        ]);

        // Sincronizando os papéis
        $user->roles()->sync($input['roles'] ?? []);

        return redirect()->route('users.index')->with('status', 'Funções atualizadas com sucesso.');
    }
    public function updateMenus(Request $request, User $user)
{
    $user->menus()->sync($request->menus ?? []);
    return redirect()->route('users.edit', $user->id)->with('success', 'Menus atualizados com sucesso!');
}
    
}




INSERT INTO menu_user
(user_id, menu_id)
VALUES(1, 1);
INSERT INTO menu_user
(user_id, menu_id)
VALUES(1, 2);
INSERT INTO menu_user
(user_id, menu_id)
VALUES(1, 3);
INSERT INTO menu_user
(user_id, menu_id)
VALUES(2, 3);


INSERT INTO menus
(id, name, created_at, updated_at)
VALUES(1, 'tecnologia', '2025-05-06 22:14:05', '2025-05-06 22:14:05');
INSERT INTO menus
(id, name, created_at, updated_at)
VALUES(2, 'qualidade', '2025-05-06 22:14:19', '2025-05-06 22:14:19');
INSERT INTO menus
(id, name, created_at, updated_at)
VALUES(3, 'rh', '2025-05-06 22:14:19', '2025-05-06 22:14:19');


INSERT INTO roles
(id, name, created_at, updated_at)
VALUES(1, 'admin', '2025-05-06 21:44:23', '2025-05-06 21:44:23');
INSERT INTO roles
(id, name, created_at, updated_at)
VALUES(2, 'edit', '2025-05-06 21:44:32', '2025-05-06 21:44:32');
INSERT INTO roles
(id, name, created_at, updated_at)
VALUES(3, 'view', '2025-05-06 21:44:53', '2025-05-06 21:44:53');

INSERT INTO role_user
(user_id, role_id)
VALUES(1, 1);
INSERT INTO role_user
(user_id, role_id)
VALUES(2, 3);

INSERT INTO users
(id, name, email, email_verified_at, password, two_factor_secret, two_factor_recovery_codes, two_factor_confirmed_at, remember_token, created_at, updated_at, avatar, status)
VALUES(1, 'Wesley Fernando Cabrera', 'wesley.cabrera@hotmail.com', NULL, '$2y$12$R52FZDj9r9aLCnUFOisoGuwFlPf1PBUG4TV3x3imxL3xhn8OepElK', NULL, NULL, NULL, NULL, '2025-04-16 02:36:18', '2025-04-16 02:36:18', NULL, 1);




-- Aprovadores de arquivos apenas o responsavel do setor envia para setor qualidade faz a triagem e devolve para conclusao  

-- Ativo aparece sempre para qualidade / processos para os usuarios oculta inativo

revisão segue automatico conforme as revisao

RNC NOVO 
formulario rnc com perguntas 
as inspetoras ira fazer a inspeçao baseado na inspeçao se tiver uma nao conformidade , gerar ocorrencia com base nas macros 

libera para todo mundo porem 

marca da agua 

marcar que a pessoa visualizou o arquivo 

depois treinamento 



INSERT INTO appz.users
	(
	name
	, email
	, status
	, avatar
	, email_verified_at
	, password
	, two_factor_secret
	, two_factor_recovery_codes
	, two_factor_confirmed_at
	, remember_token
	, created_at
	, updated_at
	, deleted_at
	)
VALUES
	(
	'Wesley Fernando Cabrera'
	, 'wesley.cabrera@hotmail.com'
	, 1
	, NULL
	, NULL
	, '$2y$12$HubdfryvjzcHyJhKZTUNye.sPtDAYM9WXRvF/ugbb0HPevDvaiSaC'
	, NULL
	, NULL
	, NULL
	, NULL
	, '2025-05-19 20:14:24'
	, '2025-05-19 20:14:24'
	, NULL
	);



INSERT INTO appz.roles
	(
	name
	, created_at
	, updated_at
	, deleted_at
	)
VALUES
	(
	'admin'
	, '2025-05-19 20:14:50'
	, '2025-05-19 20:14:50'
	, NULL
	);

INSERT INTO appz.roles
	(
	name
	, created_at
	, updated_at
	, deleted_at
	)
VALUES
	(
	'edit'
	, '2025-05-19 20:14:50'
	, '2025-05-19 20:14:50'
	, NULL
	);

INSERT INTO appz.roles
	(
	name
	, created_at
	, updated_at
	, deleted_at
	)
VALUES
	(
	'view'
	, '2025-05-19 20:14:50'
	, '2025-05-19 20:14:50'
	, NULL
	);



INSERT INTO appz.role_user
	(
	user_id
	, role_id
	)
VALUES
	(
	1
	, 1
	);


INSERT INTO appz.menus
	(
	name
	, created_at
	, updated_at
	, deleted_at
	)
VALUES
	(
	'Gestão de Tecnologia'
	, '2025-05-19 20:15:48'
	, '2025-05-19 20:15:48'
	, NULL
	);

INSERT INTO appz.menus
	(
	name
	, created_at
	, updated_at
	, deleted_at
	)
VALUES
	(
	'Gestão de Comercial'
	, '2025-05-19 20:16:13'
	, '2025-05-19 20:16:13'
	, NULL
	);

INSERT INTO appz.menus
	(
	name
	, created_at
	, updated_at
	, deleted_at
	)
VALUES
	(
	'Gestão de Recursos Humanos'
	, '2025-05-19 20:16:35'
	, '2025-05-19 20:16:35'
	, NULL
	);

INSERT INTO appz.menu_user
	(
	user_id
	, menu_id
	)
VALUES
	(
	1
	, 1
	);

