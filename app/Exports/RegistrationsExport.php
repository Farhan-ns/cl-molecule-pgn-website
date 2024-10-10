<?php

namespace App\Exports;

use App\Models\Registration;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RegistrationsExport implements FromCollection, ShouldAutoSize, WithMapping, WithHeadings, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Registration::orderBy('name')->get();
    }
    
    public function styles(Worksheet $worksheet)
    {
        return [
            1 => ['font' => ['bold' => true],],
        ];
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Nomor HP',
            'Email',
            'Jabatan',
            'Nama Perusahaan',
            'Domisili Perusahaan',
            'Akan Hadir',
            'Tanggal RSVP',
            'Telah Scan',
            'Waktu Scan',
        ];
    }

    public function map($registration): array
    {
        $additionalInfo = json_decode($registration->additional_info, true);

        return [
            Str::title($registration->name),
            $registration->phone,
            $registration->email,
            $registration->office,
            $registration->company_name,
            $registration->company_address,
            $registration->will_attend ? 'Ya' : 'Tidak',
            Carbon::parse($registration->created_at)->format('d/m/Y'),
            $registration->has_attended ? 'Ya' : 'Tidak',
            $registration->has_attended ? Carbon::parse($registration->attended_at)->setTimezone('Asia/Jakarta')->format('H:i d/m/Y') : '',
        ];
    }
}
