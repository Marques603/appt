<?php

/*
|
| Controladores de rotas da aplicação
|
*/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\MacroController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\SectorController;
use App\Http\Controllers\CostCenterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\ArchiveController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleMovementController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\AgreementsController;
use App\Http\Controllers\Agreements_typeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Rotas da aplicação protegidas por autenticação.
|
*/

Route::middleware(['auth'])->group(function () {

    Route::view('/', 'home.index')->name('home');

    // Rota dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Rotas de usuários
    Route::get('/users/export-csv', [UserController::class, 'exportCsv'])->name('users.export.csv');
    Route::get('/users/export-pdf', [UserController::class, 'exportPdf'])->name('users.export.pdf');
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

    Route::resource('position', PositionController::class)->except(['show']);
    Route::put('position/{position}/details', [PositionController::class, 'updateDetails'])->name('position.update.details');
    Route::put('position/{position}/status', [PositionController::class, 'updateStatus'])->name('position.update.status');
    Route::put('position/{position}/users', [PositionController::class, 'updateUsers'])->name('position.update.users');
    Route::get('position/tree', [PositionController::class, 'tree'])->name('position.tree');

    // Rotas de menus
    Route::resource('menus', MenuController::class);

    // Rotas de roles
    Route::resource('roles', RoleController::class);

    // Rotas de macro
    Route::resource('macro', MacroController::class);
    Route::put('/macro/{macro}/status', [MacroController::class, 'updateStatus'])->name('macro.update.status');
    Route::get('/macro/{macro}/restore', [MacroController::class, 'restore'])->name('macro.restore');
    Route::put('/macro/{macro}/responsibles', [MacroController::class, 'updateResponsibles'])->name('macro.update.responsibles');

    // Rotas de folder
    Route::resource('folders', FolderController::class);
    Route::get('folders/{folder}/sector/{sector}', [FolderController::class, 'sectorFiles'])->name('folders.sectorFiles');

    // Rotas de documentos (CRUD) - MANTIDAS
    Route::resource('documents', DocumentController::class)->except(['update']);
    Route::get('/documents/approval-index', [DocumentController::class, 'approvalIndex'])->name('documents.approval.index');
    Route::get('/documents/{document}/approve', [DocumentController::class, 'showApproveForm'])->name('documents.approve.form');
    Route::post('/documents/{document}/approve', [DocumentController::class, 'storeApproval'])->name('documents.approve.store');
    Route::post('/documents/{document}/approve/status', [DocumentController::class, 'updateApprovalStatus'])->name('documents.updateApprovalStatus');
    Route::put('documents/{document}/code', [DocumentController::class, 'updateCode'])->name('documents.update.code');
    Route::put('documents/{document}/file', [DocumentController::class, 'updateFile'])->name('documents.update.file');
    Route::put('documents/{document}/macros', [DocumentController::class, 'updateMacros'])->name('documents.update.macros');
    Route::put('documents/{document}/sectors', [DocumentController::class, 'updateSectors'])->name('documents.update.sectors');
    Route::put('/documents/{document}/status', [DocumentController::class, 'updateStatus'])->name('documents.update.status');
    Route::get('/documents/{document}/view', [DocumentController::class, 'logAndShow'])->name('documents.logAndShow');
    Route::get('/documentsapprove', [DocumentController::class, 'documentsapprove'])->name('documentsapprove.index');

    // Rotas de veículos (CRUD)
    Route::resource('archives', ArchiveController::class);

    // Rotas específicas de archives (aprovação, update detalhes)
    Route::get('/archives/{archive}/approve', [ArchiveController::class, 'showApproveForm'])->name('archives.approve.form');
    Route::post('/archives/{archive}/approve', [ArchiveController::class, 'approve'])->name('archives.approve');
    Route::post('/archives/{archive}/approve/status', [ArchiveController::class, 'updateApprovalStatus'])->name('archives.updateApprovalStatus');
    Route::put('archives/{archive}/code', [ArchiveController::class, 'updateCode'])->name('archives.update.code');
    Route::put('archives/{archive}/file', [ArchiveController::class, 'updateFile'])->name('archives.update.file');
    Route::put('archives/{archive}/folders', [ArchiveController::class, 'updateFolders'])->name('archives.update.folders');
    Route::put('archives/folders/{folder}/sectors', [ArchiveController::class, 'updateFolderSectors'])->name('archives.folders.update.sectors');
    Route::put('/archives/{archive}/status', [ArchiveController::class, 'updateStatus'])->name('archives.update.status');
    Route::get('/sectors/{sector}/archives', [SectorController::class, 'showArchives'])->name('sectors.archives.index');
    Route::get('/archives/{archive}/view', [ArchiveController::class, 'view'])->name('archives.view');
    Route::get('/archives/{archive}/download', [ArchiveController::class, 'download'])->name('archives.download');
    Route::post('/folders/{folder:slug}/subfolders/{subfolder:slug}/upload-archive', [ArchiveController::class, 'upload'])->name('archives.upload');
    Route::put('/archives/{archive}/update-sectors', [ArchiveController::class, 'updateSectors'])->name('archives.update.sectors');
    Route::get('/archives/{archive}/view', [ArchiveController::class, 'logAndShow'])->name('archives.logAndShow');
    
    // Rotas para criação e armazenamento de archives vinculados a folder + sector
    Route::get('folders/{folder}/sector/{sector}/archives/create', [ArchiveController::class, 'create'])->name('archives.create');
    Route::post('folders/{folder}/sector/{sector}/archives', [ArchiveController::class, 'store'])->name('archives.store');

    // Rotas para criação e armazenamento de archives vinculados a folder + plan
    Route::get('folders/{folder}/plan/{plan}/archives/create', [ArchiveController::class, 'create'])->name('archives.create.plan');
    Route::post('folders/{folder}/plan/{plan}/archives', [ArchiveController::class, 'store'])->name('archives.store.plan');
    Route::get('folders/{folder}/plan/{plan}', [FolderController::class, 'planFiles'])->name('folders.planFiles');
    Route::get('folders/{folder}/sector/{sector}/archives/create', [ArchiveController::class, 'create'])->name('archives.create');
    Route::get('folders/{folder}/plan/{plan}/archives/create', [ArchiveController::class, 'create'])->name('archives.create');
    Route::get('folders/{folder}/plan/{plan}/archives/create', [ArchiveController::class, 'create'])->name('archives.create');
    Route::post('folders/{folder}/plan/{plan}/archives', [ArchiveController::class, 'store'])->name('archives.store');


    // Rotas de planos (CRUD) - MANTIDAS
    Route::resource('plans', PlanController::class);
    Route::put('/plans/{plan}/update-details', [PlanController::class, 'updateDetails'])->name('plans.update.details');
    Route::put('/plans/{plan}/update-status', [PlanController::class, 'updateStatus'])->name('plans.update.status');
    Route::put('/plans/{plan}/update-users', [PlanController::class, 'updateUsers'])->name('plans.update.users');
    Route::put('/plans/{plan}/update-responsibles', [PlanController::class, 'updateResponsibles'])->name('plans.update.responsibles');
    
    // Rotas de empresas (CRUD) - MANTIDAS
    Route::resource('company', CompanyController::class);
    Route::put('/company/{company}/update-details', [CompanyController::class, 'updateDetails'])->name('company.update.details');
    Route::put('/company/{company}/update-status', [CompanyController::class, 'updateStatus'])->name('company.update.status');
    Route::put('/company/{company}/update-users', [CompanyController::class, 'updateUsers'])->name('company.update.users');
    Route::put('/company/{company}/update-responsibles', [CompanyController::class, 'updateResponsibles'])->name('company.update.responsibles');

    // Rotas de setores (CRUD) - MANTIDAS
    Route::resource('sector', SectorController::class);
    Route::put('/sector/{sector}/update-details', [SectorController::class, 'updateDetails'])->name('sector.update.details');
    Route::put('/sector/{sector}/update-status', [SectorController::class, 'updateStatus'])->name('sector.update.status');
    Route::put('/sector/{sector}/update-users', [SectorController::class, 'updateUsers'])->name('sector.update.users');
    Route::put('/sector/{sector}/update-responsibles', [SectorController::class, 'updateResponsibles'])->name('sector.update.responsibles');

    // Rotas de centros de custo (CRUD) - MANTIDAS
    Route::resource('cost_center', CostCenterController::class);
    Route::put('/cost_center/{cost_center}/update-info', [CostCenterController::class, 'updateInfo'])->name('cost_center.update.info');
    Route::put('/cost_center/{cost_center}/update-status', [CostCenterController::class, 'updateStatus'])->name('cost_center.update.status');
    Route::put('/cost_center/{cost_center}/update-sectors', [CostCenterController::class, 'updateSectors'])->name('cost_center.update.sectors');
      
    // CRUD de veículos - MANTIDAS
    Route::resource('vehicles', VehicleController::class);
    Route::get('/vehicles/export/csv', [VehicleController::class, 'exportCsv'])->name('vehicles.export.csv');
    Route::get('/vehicles/export/pdf', [VehicleController::class, 'exportPdf'])->name('vehicles.export.pdf');

    // Registro de saída de veículos - MANTIDAS
    Route::prefix('vehicles/{vehicle}')->group(function () {
        Route::get('saida', [VehicleMovementController::class, 'create'])->name('vehicles.movement.create');
        Route::post('saida', [VehicleMovementController::class, 'store'])->name('vehicles.movement.store');
    });

    // Registro de retorno de veículos - MANTIDAS
    Route::get('movements/{movement}/retorno', [VehicleMovementController::class, 'edit'])->name('vehicles.movement.edit');
    Route::put('movements/{movement}/retorno', [VehicleMovementController::class, 'update'])->name('vehicles.movement.update');
    Route::get('vehicle_movements', [VehicleMovementController::class, 'index'])->name('vehicle_movements.index');

     // Rotas de Visitantes
    Route::resource('visitors', VisitorController::class);
    Route::get('/visitors', [VisitorController::class, 'index'])->name('visitors.index');
    Route::get('/visitors/create', [VisitorController::class, 'create'])->name('visitors.create');
    Route::post('/visitors', [VisitorController::class, 'store'])->name('visitors.store');
    Route::get('/visitors/{visitor}/edit', [VisitorController::class, 'edit'])->name('visitors.edit');
    Route::put('/visitors/{visitor}', [VisitorController::class, 'update'])->name('visitors.update');
    Route::delete('/visitors/{visitor}', [VisitorController::class, 'destroy'])->name('visitors.destroy');
    Route::get('/visitors/{visitor}/show', [VisitorController::class, 'show'])->name('visitors.show');
    Route::get('/visitors2', [VisitorController::class, 'index2'])->name('visitors.index2');
    Route::get('/visitors/{visitor}/restore', [VisitorController::class, 'restore'])->name('visitors.restore');
    Route::put('/visitors/{visitor}/update-vehicles', [VisitorController::class, 'updateVehicles'])->name('visitors.update.vehicles');
    Route::put('/visitors/{visitor}/registrar-saida', [VisitorController::class, 'updatesaidastatus'])->name('visitors.updatesaidastatus');
    Route::get('/visitors/create', [VisitorController::class, 'create'])->name('visitors.create');

     // Rotas de Notas Fiscais
    Route::get('/notes', [NoteController::class, 'index'])->name('notes.index');
    Route::get('/notes/create', [NoteController::class, 'create'])->name('notes.create');
    Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
    Route::get('/notes/{note}/edit', [NoteController::class, 'edit'])->name('notes.edit');
    Route::put('/notes/{note}', [NoteController::class, 'update'])->name('notes.update');
    Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');
    Route::get('/notes/{note}/show', [NoteController::class, 'show'])->name('notes.show');
    Route::get('/notes2', [NoteController::class, 'index2'])->name('notes2.index');
    Route::get('/notes3', [NoteController::class, 'index3'])->name('notes3.index');
    Route::get('/notes/{note}/restore', [NoteController::class, 'restore'])->name('notes.restore');

    // Diretores aprovam
    Route::post('/notes/{note}/approve', [NoteController::class, 'approve'])->name('notes.approve')->middleware('can:approve,note');
    Route::post('/notes/{note}/reject', [NoteController::class, 'reject'])->name('notes.reject')->middleware('can:approve,note');
    
    // Usuário lançador
    Route::get('/notes/{note}/launch', [NoteController::class, 'launchForm'])->name('notes.launch.form')->middleware('can:launch,note');
    Route::post('/notes/{note}/launch', [NoteController::class, 'launch'])->name('notes.launch')->middleware('can:launch,note');
    
    // Pagamento
    Route::post('/notes/{note}/aprovar', [NoteController::class, 'aprovar'])->name('notes.aprovar');
    Route::post('/notes/{note}/lancar', [NoteController::class, 'lancar'])->name('notes.lancar');
    Route::post('/notes/{note}/enviar-pagamento', [NoteController::class, 'enviarPagamento'])->name('notes.enviar_pagamento');
    Route::post('/notes/{note}/pagar', [NoteController::class, 'pagar'])->name('notes.pagar');
    Route::get('/notes/export/csv', [NoteController::class, 'exportCsv'])->name('notes.export.csv');
    Route::get('/notes/export/pdf', [NoteController::class, 'exportPdf'])->name('notes.export.pdf');
    Route::get('/notes/history', [NoteController::class, 'history'])->name('notes.history');

    // Convênios
    Route::get('/agreements', [AgreementsController::class, 'index'])->name('agreements.index');
    Route::get('/agreements/create', [AgreementsController::class, 'create'])->name('agreements.create');
    Route::post('/agreements', [AgreementsController::class, 'store'])->name('agreements.store');  
    Route::get('/agreements/{agreement}/edit', [AgreementsController::class, 'edit'])->name('agreements.edit');
    Route::put('/agreements/{agreement}', [AgreementsController::class, 'update'])->name('agreements.update');
    Route::delete('/agreements/{agreement}', [AgreementsController::class, 'destroy'])->name('agreements.destroy');
    Route::get('/agreements/{agreement}/show', [AgreementsController::class, 'show'])->name('agreements.show');

    Route::get('/agreements_type', [Agreements_typeController::class, 'index'])->name('agreements_type.index');
    Route::get('/agreements_type/create', [Agreements_typeController::class, 'create'])->name('agreements_type.create');
    Route::post('/agreements_type', [Agreements_typeController::class, 'store'])->name('agreements_type.store');
    Route::get('/agreements_type/{type}/edit', [Agreements_typeController::class, 'edit'])->name('agreements_type.edit');
    Route::put('/agreements_type/{type}', [Agreements_typeController::class, 'update'])->name('agreements_type.update');
    Route::delete('/agreements_type/{type}', [Agreements_typeController::class, 'destroy'])->name('agreements_type.destroy');
    Route::get('/agreements_type/{type}/show', [Agreements_typeController::class, 'show'])->name('agreements_type.show');

    // Documentação
    Route::resource('documentation', DocumentationController::class);
    Route::get('/documentation', [DocumentationController::class, 'introduction'])->name('documentation.introduction');
    Route::get('/documentation', [DocumentationController::class, 'index'])->name('documentation.index');
    Route::get('/documentation/create', [DocumentationController::class, 'create'])->name('documentation.create');
    
});
