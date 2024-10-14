<?php

namespace App\Console\Commands;

use App\Contracts\AppContracts;
use App\Http\Services\NotificationService;
use App\Http\Services\RegisterService;
use App\Mail\VaccinationScheduled;
use App\Models\Registration;
use App\Models\VaccineCenter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UpdateScheduleDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:update-schedule-date';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $unscheduledUsers = Registration::where('status', AppContracts::STATUS_NOT_SCHEDULED)->with(['user'])->get();

            foreach ($unscheduledUsers as $registration) {
                $center = VaccineCenter::findOrfail($registration->vaccine_center_id);
                if(!empty($center)) {
                    $nextAvailableDate = (new RegisterService())->getNextAvailableDate($center);

                    if ($nextAvailableDate) {
                        $registration->update([
                            'scheduled_date' => $nextAvailableDate,
                            'status' => AppContracts::STATUS_SCHEDULED,
                        ]);
                        if(!empty($registration->user->email)) {
                            (new NotificationService(AppContracts::NOTIFICATION_MAIL, AppContracts::NOTIFICATION_UPDATE_SCHEDULE, $registration));
                            // Mail::to($registration->user->email)->send(new VaccinationScheduled($registration));
                            if(env('SMS_NOTIFICATION_ENABLED')) {
                                (new NotificationService(AppContracts::NOTIFICATION_SMS, AppContracts::NOTIFICATION_UPDATE_SCHEDULE, $registration));
                            }
                        }
                    }
                }
            }
        } catch (\Throwable $th) {
            Log::error('MAIL_SENDING_FAILED_FOR_VACCINATION', ['error' => $th->getMessage()]);
        }
    }
}
