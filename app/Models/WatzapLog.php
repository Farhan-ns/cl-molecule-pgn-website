<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WatzapLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'response_log',
        'registration_id',
    ];

    public function registration()
    {
        return $this->belongsTo(Registration::class);
    }
}
