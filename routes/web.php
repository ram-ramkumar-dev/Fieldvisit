<?php

use App\Http\Controllers\BatchesController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientGroupController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/ 

Route::get('/', [UserController::class, 'home'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');
    //Route::get('/users', [UserController::class, 'users'])->name('users');
    //Route::get('/clients', [UserController::class, 'clients'])->name('clients');
    Route::get('password', [UserController::class, 'password'])->name('password');
    Route::post('password', [UserController::class, 'password_action'])->name('password.action');
    Route::get('logout', [UserController::class, 'logout'])->name('logout');

    // Additional routes with permission checks
   // Route::get('reports', [UserController::class, 'reports'])->name('reports')->middleware('check.permissions:reports');
    Route::get('settings', [UserController::class, 'settings'])->name('settings')->middleware('check.permissions:settings');
    Route::get('administrative', [UserController::class, 'administrative'])->name('administrative')->middleware('check.permissions:administrative'); 
    Route::get('/batches/import', [BatchesController::class, 'import'])->name('batches.import');
    Route::get('/batches/assign', [BatchesController::class, 'BatchesList'])->name('batches.assign'); 
    Route::get('getBatchProgressForChart03', [BatchesController::class, 'getBatchProgressForChart03'])->name('getBatchProgressForChart03');  
    Route::get('/agentKpi', [ReportsController::class, 'agentKpi'])->name('reports.agentKpi');
    Route::post('/handle-form', [ReportsController::class, 'handleForm'])->name('handle.form');
});

Route::get('register', [UserController::class, 'register'])->name('register');
Route::post('register', [UserController::class, 'register_action'])->name('register.action');
Route::get('login', [UserController::class, 'login'])->name('login');
Route::post('login', [UserController::class, 'login_action'])->name('login.action');


Route::get('/api/documentation', function () {
    return view('swagger.index');
});

Route::resource('batches', BatchesController::class);
Route::resource('clientgroups', ClientGroupController::class);
Route::resource('clients', ClientController::class);
Route::resource('drivers', DriverController::class);
Route::resource('statuses', StatusController::class);

Route::get('/batches/{batch}/viewuploaded', [BatchesController::class, 'viewUploaded'])->name('batches.viewuploaded');
Route::get('/batches/{batch}/upload', [BatchesController::class, 'showUploadForm'])->name('batches.upload');
Route::post('/batches/{batch}/upload', [BatchesController::class, 'uploadBatchDetails'])->name('batches.upload.store');
Route::delete('/batches/{id}/details', [BatchesController::class, 'deleteBatchDetails'])->name('batches.details.delete');
Route::get('/batches/{batch}/assigncase', [BatchesController::class, 'AssignCase'])->name('batches.assigncase');

Route::post('/batches/assignbatchestodrivers', [BatchesController::class, 'assignBatchesToDrivers'])->name('batches.assignbatchestodrivers');



