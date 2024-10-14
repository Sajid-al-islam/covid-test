<?php

namespace App\Http\Services;

use App\Contracts\AppContracts;
use App\Models\Registration;
use App\Models\User;
use App\Models\VaccineCenter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisterService
{
    /**
     * Create a new class instance.
     */
    public function __construct() {}

    public function handleRegistration($request)
    {
        $return_status = 'error';
        $return_message = 'Failed';

        try {
            $log_info = [];
            $log_info['action'] = 'USER_REGISTRATION';
            $log_info['data'] = $request->input();

            DB::beginTransaction();

            // Check if the user is already registered by NID
            if (User::where('nid', $request->nid)->exists()) {
                return redirect()->back()->withErrors('User already registered');
            }

            // Register the user
            $user = User::create([
                'name' => $request->name,
                'mobile_number' => $request->mobile_number,
                'email' => $request->email,
                'nid' => $request->nid,
                'password' => Hash::make($request->mobile_number),
            ]);

            // Get the selected vaccine center from the request
            $center = VaccineCenter::findOrFail($request->vaccine_center_id);


            $scheduledDate = $this->getNextAvailableDate($center);


            $status = $scheduledDate ? AppContracts::STATUS_SCHEDULED : AppContracts::STATUS_NOT_SCHEDULED;

            $registration = Registration::create([
                'user_id' => $user->id,
                'vaccine_center_id' => $center->id,
                'scheduled_date' => $scheduledDate,
                'status' => $status,
            ]);


            if ($status === AppContracts::STATUS_SCHEDULED) {
                $return_status = 'success';
                $return_message = 'You have been successfully registered and scheduled for vaccination on ' . $scheduledDate->toFormattedDateString() . '.';
            } else {
                $return_status = 'info';
                $return_message = 'You have been registered but no slots are currently available. We will notify you when a slot becomes available.';
            }

            DB::commit();
        } catch (\Throwable $th) {
            $return_status = 'error';
            $return_message = "Something went wrong! Please try again later.";
            $log_info['error'] = $this->getFullMessage($th);

            DB::rollBack();
            Log::error("USER_REGISTRATION_FAILED", $log_info);
        }

        return [$return_status, $return_message];
    }

    public function getNextAvailableDate(VaccineCenter $center)
    {
        $limit = $center->capacity;
        $nextAvailableDate = now()->copy()->startOfDay();
        $maxDate = now()->addMonths(6);

        while ($nextAvailableDate->lessThanOrEqualTo($maxDate)) {
            if ($nextAvailableDate->isWeekend()) {
                $nextAvailableDate->addDay();
                continue;
            }

            $countForDay = Registration::where('vaccine_center_id', $center->id)
            ->where('scheduled_date', $nextAvailableDate)
            ->count();

            if ($countForDay < $limit) {
                return $nextAvailableDate;
            }

            $nextAvailableDate->addDay();
        }

        return null;
    }

    private function getFullMessage(\Throwable $throwable)
    {
        return $throwable->getMessage() . " " . $throwable->getFile() . " " . $throwable->getLine();
    }
}
