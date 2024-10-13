<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Http\Services\RegisterService;
use App\Models\Registration;
use App\Models\User;
use App\Models\VaccineCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class RegistrationController extends Controller
{
    public function index(RegisterUserRequest $request) {
        [$status, $message] = (new RegisterService)->handleRegistration($request);
        return redirect()->back()->with($status, $message);
    }
}
