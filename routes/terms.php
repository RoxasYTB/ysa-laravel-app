<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TermsController;

Route::get('/terms', [TermsController::class, 'index'])->name('terms');

