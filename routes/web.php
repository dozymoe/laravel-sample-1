<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', 'App\Http\Controllers\AuthController@loginForm')
    ->name('login');
Route::post('login', 'App\Http\Controllers\AuthController@doLogin');
Route::get('logout', 'App\Http\Controllers\AuthController@doLogout')
    ->name('logout');
Route::post('logout', 'App\Http\Controllers\AuthController@doLogout');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', 'App\Http\Controllers\DashboardController@index')
        ->name('dashboard');
});

// CRUD for User
Route::prefix('user')->group(function () {
    Route::get('{object}/edit',
        'App\Http\Controllers\UserController@updateForm')
        ->middleware('can:update,object')
        ->name('user.update');
    Route::post('{object}/edit',
        'App\Http\Controllers\UserController@doUpdate')
        ->middleware('can:update,object');

    Route::get('{object}/delete',
        'App\Http\Controllers\UserController@deleteForm')
        ->middleware('can:delete,object')
        ->name('user.delete');
    Route::post('{object}/delete',
        'App\Http\Controllers\UserController@doDelete')
        ->middleware('can:delete,object');
});
