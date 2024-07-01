<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\DriverController;
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
    Route::get('/users', [UserController::class, 'users'])->name('users');
    Route::get('/clients', [UserController::class, 'clients'])->name('clients');
    Route::get('password', [UserController::class, 'password'])->name('password');
    Route::post('password', [UserController::class, 'password_action'])->name('password.action');
    Route::get('logout', [UserController::class, 'logout'])->name('logout');

    // Additional routes with permission checks
    Route::get('reports', [UserController::class, 'reports'])->name('reports')->middleware('check.permissions:reports');
    Route::get('settings', [UserController::class, 'settings'])->name('settings')->middleware('check.permissions:settings');
    Route::get('administrative', [UserController::class, 'administrative'])->name('administrative')->middleware('check.permissions:administrative');
});

Route::get('register', [UserController::class, 'register'])->name('register');
Route::post('register', [UserController::class, 'register_action'])->name('register.action');
Route::get('login', [UserController::class, 'login'])->name('login');
Route::post('login', [UserController::class, 'login_action'])->name('login.action');

Route::resource('drivers', DriverController::class);
Route::get('/api/documentation', function () {
    return view('swagger.index');
});