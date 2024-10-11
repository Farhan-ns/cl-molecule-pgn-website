<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registration extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'company_industry',
        'company_name',
        'office',
        'company_phone',
        'company_email',
        'company_address',
        'workshop_question',
        'event_info_source',
        'attendance_lateness',
        'additional_info',
        // 'office_input',
        // 'event_info_source_input',
        // 'company_industry_input',
    ];

    protected $appends = [
        'will_attend',
    ];

    public function getWillAttendAttribute()
    {
        $json = json_decode($this->additional_info, true);

        return $json['will_attend'] ?? '';
    }

    /**
     * Get or generate a unique code for the user.
     *
     * @return string
     */
    public function getUniqueCode()
    {
        if ($this->unique_code) {
            return $this->unique_code;
        }

        $this->unique_code = $this->generateUniqueCode();
        $this->save();

        return $this->unique_code;
    }

    /**
     * Generate a unique code.
     *
     * @return string
     */
    private function generateUniqueCode()
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $codeLength = 6;

        do {
            $code = '';
            for ($i = 0; $i < $codeLength; $i++) {
                $code .= $characters[rand(0, strlen($characters) - 1)];
            }
        } while (Registration::where('unique_code', $code)->exists());

        return $code;
    }
}
