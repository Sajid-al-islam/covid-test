<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterUserRequest;
use App\Http\Services\RegisterService;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class RegistrationController extends Controller
{
    public function submit_registraion(RegisterUserRequest $request) {
        [$status, $message] = (new RegisterService)->handleRegistration($request);
        return Redirect::to(URL::previous() . "#registration")->with($status, $message);
    }
}
