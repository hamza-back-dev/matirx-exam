<?php

use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');




Route::middleware('auth')->group(function(){
   

    Route::macro('softDeletes', function () {
        Route::get('/users/trashed', 'App\Http\Controllers\UsersController@trashed')->name('users.trashed');
        Route::patch('/users/{user}/restore', 'App\Http\Controllers\UsersController@restore')->name('users.restore');
        Route::delete('/users/{user}/delete', 'App\Http\Controllers\UsersController@delete')->name('users.delete');
    });

    Route::softDeletes('users', UsersController::class);

    Route::resource('users', UsersController::class);
    Route::get('/users/{id}/destory', [UsersController::class, 'destroy'])->name('users.destroyUser');

   
});




