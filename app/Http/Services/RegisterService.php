<?php

namespace App\Http\Services;

use App\Models\Registration;
use App\Models\User;
use App\Models\VaccineCenter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisterService
{
    const STATUS_NOT_REGISTERED = "Not registered";
    const STATUS_NOT_SCHEDULED = "Not Scheduled";
    const STATUS_SCHEDULED = "Scheduled";
    const STATUS_VACINATED = "Vaccinated";
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

            $centers = VaccineCenter::get();
            $scheduledDate = null;
            $vaccineCenterId = null;

            foreach ($centers as $center) {
                // Try to get the next available date
                $nextAvailableDate = $this->getNextAvailableDate($center);

                if ($nextAvailableDate) {
                    $scheduledDate = $nextAvailableDate;
                    $vaccineCenterId = $center->id;
                    break;
                }
            }

            $status = $scheduledDate ? self::STATUS_SCHEDULED : self::STATUS_NOT_SCHEDULED;

            $registration = Registration::create([
                'vaccine_center_id' => $vaccineCenterId,
                'scheduled_date' => $scheduledDate,
                'status' => $status,
            ]);

            if ($status === self::STATUS_SCHEDULED) {
                $return_status = 'success';
                $return_message = 'You have been successfully registered and scheduled for vaccination.';
            }

            $return_status = 'info';
            $return_message = 'You have been registered but not yet scheduled. We will notify you when a slot becomes available.';
            DB::commit();
        } catch (\Throwable $th) {
            $return_status = 'error';
            $return_message = "Something went wrong! please try again later";
            $log_info['error'] = $this->getFullMessage($th);

            DB::rollBack();
            Log::error("USER_REGISTRATION_FAILED", $log_info);
        }

        return [$return_status, $return_message];
    }

    private function getNextAvailableDate(VaccineCenter $center)
    {
        $limit = $center->daily_limit;
        $nextAvailableDate = now()->copy()->startOfDay();
        $maxDate = now()->addMonths(6);

        while ($nextAvailableDate->lessThanOrEqualTo($maxDate)) {
            // Skip weekends (Friday, Saturday in some locales)
            if ($nextAvailableDate->isWeekend()) {
                $nextAvailableDate->addDay();
                continue;
            }

            // Check how many users are already scheduled for this date
            $countForDay = Registration::where('vaccine_center_id', $center->id)
                ->whereDate('scheduled_date', $nextAvailableDate)
                ->count();

            // If there's room in the center for that day, return the date
            if ($countForDay < $limit) {
                return $nextAvailableDate;
            }

            // Move to the next day
            $nextAvailableDate->addDay();
        }

        // Return null if no available date is found within the max range
        return null;
    }

    private function getFullMessage(\Throwable $throwable)
    {
        return $throwable->getMessage() . " " . $throwable->getFile() . " " . $throwable->getLine();
    }
}
