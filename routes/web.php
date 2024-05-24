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
