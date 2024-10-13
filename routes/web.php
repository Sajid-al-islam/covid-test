<?php

use App\Http\Controllers\RegistrationController;
use App\Models\VaccineCenter;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $centers = VaccineCenter::get();
    return view('index', compact('centers'));
});

Route::post('/registration-submit', [RegistrationController::class, 'index'])->name('registration_submit');
