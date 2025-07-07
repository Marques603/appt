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

    // Rotas de cargos 
    Route::resource('position', PositionController::class);
    Route::put('position/{position}/details', [PositionController::class, 'updateDetails'])->name('position.update.details'); 
    Route::put('position/{position}/status', [PositionController::class, 'updateStatus'])->name('position.update.status');
    Route::put('position/{position}/users', [PositionController::class, 'updateUsers'])->name('position.update.users'); 

    // Rotas de menus 
    Route::resource('menus', MenuController::class);
    Route::resource('roles', RoleController::class);

    // Rotas de macro 
    Route::resource('macro', MacroController::class);
    Route::put('/macro/{macro}/status', [MacroController::class, 'updateStatus'])->name('macro.update.status'); 
    Route::get('/macro/{macro}/restore', [MacroController::class, 'restore'])->name('macro.restore'); 
    Route::put('/macro/{macro}/responsibles', [MacroController::class, 'updateResponsibles'])->name('macro.update.responsibles'); 

    // Rotas de folder 
    Route::resource('folders', FolderController::class);// Rotas de documentos 
    Route::get('folders/{folder}/sector/{sector}', [FolderController::class, 'sectorFiles'])
    ->name('folders.sectorFiles');


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
    

    // As outras rotas específicas de archives (aprovação, update de detalhes) - MANTIDAS
    Route::resource('archives', ArchiveController::class);

    Route::get('/archives/{archive}/approve', [ArchiveController::class, 'showApproveForm'])->name('archives.approve.form'); 
    Route::post('/archives/{archive}/approve', [ArchiveController::class, 'approve'])->name('archives.approve');
    Route::post('/archives/{archive}/approve/status', [ArchiveController::class, 'updateApprovalStatus'])->name('archives.updateApprovalStatus'); 
    Route::put('archives/{archive}/code', [ArchiveController::class, 'updateCode'])->name('archives.update.code'); 
    Route::put('archives/{archive}/file', [ArchiveController::class, 'updateFile'])->name('archives.update.file'); 
    Route::put('archives/{archive}/folders', [ArchiveController::class, 'updateFolders'])->name('archives.update.folders'); 
    Route::put('archives/folders/{folder}/sectors', [ArchiveController::class, 'updateFolderSectors'])->name('archives.folders.update.sectors'); 
    Route::put('/archives/{archive}/status', [ArchiveController::class, 'updateStatus'])->name('archives.update.status'); 
    Route::get('/documentsapprove', [DocumentController::class, 'documentsapprove'])->name('documentsapprove.index'); 
    Route::get('/sectors/{sector}/archives', [SectorController::class, 'showArchives'])->name('sectors.archives.index'); 
    Route::get('/archives/{archive}/view', [ArchiveController::class, 'view'])->name('archives.view'); 
    Route::get('/archives/{archive}/download', [ArchiveController::class, 'download'])->name('archives.download'); 
    Route::post('/folders/{folder:slug}/subfolders/{subfolder:slug}/upload-archive', [ArchiveController::class, 'upload'])->name('archives.upload'); 
    Route::put('/archives/{archive}/update-sectors', [ArchiveController::class, 'updateSectors'])->name('archives.update.sectors'); 
    // Rota exemplo no web.php
Route::get('folders/{folder}/sector/{sector}/archives/create', [ArchiveController::class, 'create'])->name('archives.create');
Route::post('folders/{folder}/sector/{sector}/archives', [ArchiveController::class, 'store'])->name('archives.store');
Route::get('/archives/{archive}', [ArchiveController::class, 'show'])->name('archives.show');
Route::get('archives/{archive}/download', [ArchiveController::class, 'download'])->name('archives.download');


    // Rotas de planos (crud) - MANTIDAS
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

    // Rotas de setores (CRUD) - MANTIDAS (inclui seu SectorController completo)
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

});