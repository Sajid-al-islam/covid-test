<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $guarded = [];

    const STATUS_NOT_REGISTERED = "Not registered";
    const STATUS_NOT_SCHEDULED = "Not Scheduled";
    const STATUS_SCHEDULED = "Scheduled";
    const STATUS_VACINATED = "Vaccinated";

    public function vaccineCenter()
    {
        return $this->belongsTo(VaccineCenter::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
