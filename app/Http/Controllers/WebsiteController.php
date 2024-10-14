<?php

namespace App\Http\Controllers;

use App\Contracts\AppContracts;
use App\Models\Registration;
use App\Models\User;
use App\Models\VaccineCenter;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function index()
    {
        $centers = VaccineCenter::get();
        return view('index', compact('centers'));
    }

    public function search_page()
    {
        return view('search');
    }

    public function search(Request $request)
    {
        $user = User::where('nid', $request->nid)->first();
        $result = [];

        if (!$user) {
            $result = ['status' => AppContracts::STATUS_NOT_REGISTERED];
        } else {
            $registration = $user->registration;

            if (!$registration) {
                $result = ['status' => AppContracts::STATUS_NOT_SCHEDULED];
            } else {
                if ($registration->status == AppContracts::STATUS_SCHEDULED) {
                    $result = [
                        'status' => AppContracts::STATUS_SCHEDULED,
                        'date' => $registration->scheduled_date
                    ];
                }

                if ($registration->status == AppContracts::STATUS_VACINATED) {
                    $result = [
                        'status' => AppContracts::STATUS_VACINATED,
                        'date' => $registration->scheduled_date
                    ];
                }

                if (Carbon::parse($registration->scheduled_date)->isBefore(today())) {
                    $registration->update(['status' => AppContracts::STATUS_VACINATED]);
                    $result = ['status' => AppContracts::STATUS_VACINATED];
                }
            }
        }

        return view('search', compact('result'));
    }
}
