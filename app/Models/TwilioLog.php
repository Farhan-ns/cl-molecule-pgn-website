<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TwilioLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'response_log',
        'registration_id',
    ];
}
