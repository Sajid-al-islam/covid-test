<?php

namespace App\Contracts;

interface AppContracts
{
    const STATUS_NOT_REGISTERED = "Not registered";
    const STATUS_NOT_SCHEDULED = "Not Scheduled";
    const STATUS_SCHEDULED = "Scheduled";
    const STATUS_VACINATED = "Vaccinated";
    const NOTIFICATION_MAIL = "email";
    const NOTIFICATION_SMS = "sms";
    const NOTIFICATION_VACINATION_SCHEDULE = "vacination_schedule";
    const NOTIFICATION_UPDATE_SCHEDULE = "update_schedule";
}
