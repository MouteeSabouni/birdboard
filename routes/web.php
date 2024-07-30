<?php

use App\Http\Controllers\ProjectsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => 'auth'], function () {
    Route::resource('projects', ProjectsController::class);

    Route::post('/projects/{project}/tasks', [App\Http\Controllers\ProjectTasksController::class, 'store']);
    Route::patch('/projects/{project}/tasks/{task}', [App\Http\Controllers\ProjectTasksController::class, 'update']);

    Route::post('/projects/{project}/invite', [App\Http\Controllers\ProjectInvitationsController::class, 'store']);

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

Auth::routes();
