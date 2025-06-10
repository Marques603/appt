<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MacroController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\CostCenterController;
use App\Http\Controllers\CategorieController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Rotas da aplicação protegidas por autenticação.
|
*/

Route::middleware(['auth'])->group(function () {

    // Dashboard principal
    Route::view('/', 'dashboard.index')->name('dashboard');

    // Rotas de usuários (CRUD)
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users/create', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::put('/user/{user}/password', [UserController::class, 'updatePassword'])->name('user.password.update');
    Route::put('/users/{user}/update-profile', [UserController::class, 'updateProfile'])->name('users.update.profile');
    Route::put('/users/{user}/roles', [UserController::class, 'updateRoles'])->name('users.update.roles');
    Route::put('/users/{user}/status', [UserController::class, 'updateStatus'])->name('users.update.status');
    Route::put('/users/{user}/menus', [UserController::class, 'updateMenus'])->name('users.update.menus');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::put('/users/{user}/avatar', [UserController::class, 'updateAvatar'])->name('users.update.avatar');


    // Rotas de cargos (CRUD)
    Route::resource('position', PositionController::class);
    Route::put('position/{position}/details', [PositionController::class, 'updateDetails'])->name('position.update.details');
    Route::put('position/{position}/status', [PositionController::class, 'updateStatus'])->name('position.update.status');
    Route::put('position/{position}/users', [PositionController::class, 'updateUsers'])->name('position.update.users');


    // Rotas de menus (CRUD)
    Route::resource('menus', MenuController::class);

    // Rotas de macro (CRUD)
    Route::resource('macro', MacroController::class);
    Route::put('/macro/{macro}/status', [MacroController::class, 'updateStatus'])->name('macro.update.status');
    Route::get('/macro/{macro}/restore', [MacroController::class, 'restore'])->name('macro.restore');
    Route::put('/macro/{macro}/responsibles', [MacroController::class, 'updateResponsibles'])->name('macro.update.responsibles');

    // Rotas de categorie (CRUD)
    Route::resource('categorie', CategorieController::class);
    Route::put('/categorie/{categorie}/status', [CategorieController::class, 'updateStatus'])->name('categorie.update.status');
    Route::get('/categorie/{categorie}/restore', [CategorieController::class, 'restore'])->name('categorie.restore');
    Route::put('/categorie/{categorie}/responsibles', [CategorieController::class, 'updateResponsibles'])->name('categorie.update.responsibles');


    // Rotas de documentos (CRUD)
    Route::resource('documents', DocumentController::class);
    Route::get('/documents/{document}/approve', [DocumentController::class, 'showApproveForm'])->name('documents.approve.form');
    Route::post('/documents/{document}/approve', [DocumentController::class, 'approve'])->name('documents.approve');
    Route::post('/documents/{document}/approve/status', [DocumentController::class, 'updateApprovalStatus'])->name('documents.updateApprovalStatus');
    Route::put('documents/{document}/code', [DocumentController::class, 'updateCode'])->name('documents.update.code');
    Route::put('documents/{document}/file', [DocumentController::class, 'updateFile'])->name('documents.update.file');
    Route::put('documents/{document}/macros', [DocumentController::class, 'updateMacros'])->name('documents.update.macros');
    Route::put('documents/{document}/sectors', [DocumentController::class, 'updateSectors'])->name('documents.update.sectors');
    Route::put('/documents/{document}/status', [DocumentController::class, 'updateStatus'])->name('documents.update.status');
    Route::get('/documents/{document}/view', [DocumentController::class, 'logAndShow'])->name('documents.logAndShow');




    // Rotas de empresas (CRUD)
    Route::resource('company', CompanyController::class);
    Route::put('/company/{company}/update-details', [CompanyController::class, 'updateDetails'])->name('company.update.details');
    Route::put('/company/{company}/update-status', [CompanyController::class, 'updateStatus'])->name('company.update.status');
    Route::put('/company/{company}/update-users', [CompanyController::class, 'updateUsers'])->name('company.update.users');
    Route::put('/company/{company}/update-responsibles', [CompanyController::class, 'updateResponsibles'])->name('company.update.responsibles');


    // Rotas de setores (CRUD)
    Route::resource('sector', SectorController::class);
    Route::put('/sector/{sector}/update-details', [SectorController::class, 'updateDetails'])->name('sector.update.details');
    Route::put('/sector/{sector}/update-status', [SectorController::class, 'updateStatus'])->name('sector.update.status');
    Route::put('/sector/{sector}/update-users', [SectorController::class, 'updateUsers'])->name('sector.update.users');
    Route::put('/sector/{sector}/update-responsibles', [SectorController::class, 'updateResponsibles'])->name('sector.update.responsibles');

    // Rotas de centros de custo (CRUD)
    Route::resource('cost_center', CostCenterController::class);
    Route::put('/cost_center/{cost_center}/update-info', [CostCenterController::class, 'updateInfo'])->name('cost_center.update.info');
    Route::put('/cost_center/{cost_center}/update-status', [CostCenterController::class, 'updateStatus'])->name('cost_center.update.status');
    Route::put('/cost_center/{cost_center}/update-sectors', [CostCenterController::class, 'updateSectors'])->name('cost_center.update.sectors');


});
