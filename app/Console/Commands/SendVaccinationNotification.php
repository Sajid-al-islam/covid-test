<?php

namespace App\Console\Commands;

use App\Contracts\AppContracts;
use App\Http\Services\NotificationService;
use App\Mail\VaccinationScheduled;
use App\Models\Registration;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendVaccinationNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:send-vaccination-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notification to the user the date of vaccination';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $registrations = Registration::whereDate('scheduled_date', '=', now()->addDay())
                                      ->with(['user'])->get();
            foreach ($registrations as $registration) {
                if(!empty($registration->user->email)) {

                    (new NotificationService(AppContracts::NOTIFICATION_MAIL, AppContracts::NOTIFICATION_VACINATION_SCHEDULE, $registration));
                    // Mail::to($registration->user->email)->send(new VaccinationScheduled($registration));
                    if(env('SMS_NOTIFICATION_ENABLED')) {
                        (new NotificationService(AppContracts::NOTIFICATION_SMS, AppContracts::NOTIFICATION_VACINATION_SCHEDULE, $registration));
                    }
                }
            }
        } catch (\Throwable $th) {
            Log::error('MAIL_SENDING_FAILED_FOR_VACCINATION', ['error' => $th->getMessage()]);
        }
    }
}
