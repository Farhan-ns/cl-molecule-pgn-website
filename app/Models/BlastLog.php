<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlastLog extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'response_log',
        'blast_id',
    ];
}
