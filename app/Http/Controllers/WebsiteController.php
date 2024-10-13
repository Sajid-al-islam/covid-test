<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use App\Models\User;
use App\Models\VaccineCenter;
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
            $result = ['status' => Registration::STATUS_NOT_REGISTERED];
        } else {
            $registration = $user->registration;

            if (!$registration) {
                $result = ['status' => Registration::STATUS_NOT_SCHEDULED];
            } else {
                if ($registration->status == Registration::STATUS_SCHEDULED) {
                    $result = [
                        'status' => 'Scheduled',
                        'date' => $registration->scheduled_date
                    ];
                }

                if ($registration->scheduled_date < now()) {
                    $registration->update(['status' => Registration::STATUS_VACINATED]);
                    $result = ['status' => Registration::STATUS_VACINATED];
                }
            }
        }

        return view('search', compact('result'));
    }
}
