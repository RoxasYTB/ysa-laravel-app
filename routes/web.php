<?php

use App\Http\Controllers\IdeaController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TermsController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\AntiClickjacking; 
use App\Http\Middleware\ContentSecurityPolicy;  
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LogController;

Route::middleware([AntiClickjacking::class, ContentSecurityPolicy::class])->group(function () {
    Route::get('/', function () {
        return view('welcome');
    });
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');
    
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
    
    Route::get('/terms', [TermsController::class, 'auth.terms']);
    
    Route::resource('ideas', IdeaController::class)
        ->only(['index', 'store', 'edit', 'destroy', 'update'])
        ->middleware(['auth', 'verified']);
    
    Route::resource('comments', CommentController::class)
        ->only(['index', 'store', 'edit', 'destroy', 'update'])
        ->middleware(['auth', 'verified']);
    
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    
    require __DIR__.'/auth.php';
});

Route::middleware(['auth'])->group(function () {
    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
});


    


