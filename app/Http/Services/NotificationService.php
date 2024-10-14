<?php

namespace App\Http\Services;

use App\Contracts\AppContracts;
use App\Mail\VaccinationScheduled;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    /**
     * Create a new class instance.
     */
    public $notification_type;
    public $notification;
    public $data;
    public function __construct($notification_type, $notification, $data)
    {
        $this->notification_type = $notification_type;
        $this->notification = $notification;
        $this->data = $data;
        $this->process();
    }

    private function process() {
        if($this->notification_type == AppContracts::NOTIFICATION_MAIL) {
            $this->sendEmail($this->data);
        }

        if($this->notification_type == AppContracts::NOTIFICATION_SMS) {
            $this->sendSMS($this->data);
        }
    }

    public function sendEmail($data) {
        if($this->notification == AppContracts::NOTIFICATION_UPDATE_SCHEDULE) {
            Mail::to($data->user->email)->send(new VaccinationScheduled($data));
        }
        if($this->notification == AppContracts::NOTIFICATION_VACINATION_SCHEDULE) {
            Mail::to($data->user->email)->send(new VaccinationScheduled($data));
        }

    }

    public function sendSMS() {
        // FOR future implementation of sms
    }
}
