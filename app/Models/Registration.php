<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

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
        'image_url',
        'bpc',
        'membership',
    ];

    public function getImageUrlAttribute()
    {
        $json = json_decode($this->additional_info, true);

        if ($json['image'] ?? false) {
            $image = $json['image'];
            return asset('images/' . $image);
        } else {
            return 'https://dummyimage.com/500x500/d3d3d3/fff&text=Tidak+ada+gambar';
        }
    }

    public function getWillAttendAttribute()
    {
        $json = json_decode($this->additional_info, true);

        return $json['will_attend'] ?? '';
    }

    public function getBpcAttribute()
    {
        $json = json_decode($this->additional_info, true);

        return $json['bpc'] ?? '';
    }

    public function getMembershipAttribute()
    {
        $json = json_decode($this->additional_info, true);

        return $json['membership'] ?? '';
    }
}
