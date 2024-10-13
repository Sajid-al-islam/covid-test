<?php

use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\WebsiteController;
use App\Models\VaccineCenter;
use Illuminate\Support\Facades\Route;

Route::get('/', [WebsiteController::class, 'index'])->name('home');


Route::get('search', [WebsiteController::class, 'search_page'])->name('search_page');
Route::post('search', [WebsiteController::class, 'search'])->name('search');
Route::post('/registration-submit', [RegistrationController::class, 'submit_registraion'])->name('registration_submit');
