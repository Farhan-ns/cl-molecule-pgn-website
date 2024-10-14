<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function showChartPage()
    {
        $companies = json_decode($this->readRawFile('companies-domicile.json'), true);

        $registrations = Registration::select('company_name', 'additional_info', 'has_attended')
            ->get()
            ->keyBy('company_name');

        $data['attendanceData'] = json_encode($this->getAttendanceData($companies, $registrations));
        $data['membershipData'] = json_encode($this->getMembershipData($companies, $registrations));
        $data['areaData'] = json_encode($this->getAreaData($companies, $registrations));
        $data['scanData'] = json_encode($this->getScanData($registrations));

        return view('admin.chart.index', $data);
    }

    private function getScanData($registrations)
    {
        $scanData = [
            'Telah Scan' => $registrations->where('has_attended', true)->count(),
            'Belum Scan' => $registrations->where('has_attended', false)->count(),
        ];

        $scanData = [
            'labels' => ['Telah Scan', 'Belum Scan'],
            'datasets' => array_values($scanData)
        ];

        return $scanData;
    }


    private function getAttendanceData($companies, $registrations)
    {
        $attendanceData = [
            'Hadir' => 0,
            'Tidak Hadir' => 0,
            'Belum Konfirmasi' => 0,
        ];

        foreach ($companies as $company) {
            $companyName = $company['Nama'];
            $registration = $registrations->get($companyName);

            if ($registration) {
                $willAttend = $registration->getWillAttendAttribute();

                if ($willAttend == 1) {
                    $attendanceData['Hadir']++;
                } else {
                    $attendanceData['Tidak Hadir']++;
                }
            } else {
                $attendanceData['Belum Konfirmasi']++;
            }
        }

        $labels = [
            'Hadir',
            'Tidak Hadir',
            'Belum Konfirmasi',
        ];

        $dataset = array_values($attendanceData);

        return [
            'labels' => $labels,
            'datasets' => $dataset,
        ];
    }

    private function getMembershipData($companies, $registrations)
    {
        // Iterate through the companies and collect the membership statistics
        foreach ($companies as $company) {
            $companyName = $company['Nama'];
            $membershipLevel = $company['Kelompok Pelanggan'];

            // Default status is 'Belum Konfirmasi'
            $status = 'Belum Konfirmasi';

            // Check if the company is in the registrations table
            if (isset($registrations[$companyName])) {
                $willAttend = $registrations[$companyName]->getWillAttendAttribute();
                $status = $willAttend == 1 ? 'Hadir' : 'Tidak Hadir';
            }

            // Initialize the membership level if not already done
            if (!isset($membershipStats[$membershipLevel])) {
                $membershipStats[$membershipLevel] = [
                    'Hadir' => 0,
                    'Tidak Hadir' => 0,
                    'Belum Konfirmasi' => 0,
                ];
            }

            // Increment the count based on attendance status
            $membershipStats[$membershipLevel][$status]++;
        }

        // Prepare the output for membershipData
        $membershipData = [
            'labels' => array_keys($membershipStats), // Membership levels as labels
            'datasets' => [
                [
                    'label' => 'Hadir',
                    'data' => array_column($membershipStats, 'Hadir'), // Get 'Hadir' counts for each membership level
                    'backgroundColor' => '#2980b9', // Optionally add colors
                ],
                [
                    'label' => 'Tidak Hadir',
                    'data' => array_column($membershipStats, 'Tidak Hadir'), // Get 'Tidak Hadir' counts for each membership level
                    'backgroundColor' => '#e74c3c', // Optionally add colors
                ],
                [
                    'label' => 'Belum Konfirmasi',
                    'data' => array_column($membershipStats, 'Belum Konfirmasi'), // Get 'Belum Konfirmasi' counts for each membership level
                    'backgroundColor' => '#9aba57', // Optionally add colors
                ],
            ]
        ];

        return $membershipData;
    }

    private function getAreaData($companies, $registrations)
    {
        $areaStats = [];

        // Iterate through the companies and collect the area statistics
        foreach ($companies as $company) {
            $companyName = $company['Nama'];
            $companyArea = strtoupper($company['Area']); // Standardize area formatting

            // Default status is 'Belum Konfirmasi'
            $status = 'Belum Konfirmasi';

            // Check if the company is in the registrations table
            if (isset($registrations[$companyName])) {
                $willAttend = $registrations[$companyName]->getWillAttendAttribute();
                $status = $willAttend == 1 ? 'Hadir' : 'Tidak Hadir';
            }

            // Initialize the area if not already done
            if (!isset($areaStats[$companyArea])) {
                $areaStats[$companyArea] = [
                    'Hadir' => 0,
                    'Tidak Hadir' => 0,
                    'Belum Konfirmasi' => 0,
                ];
            }

            // Increment the count based on attendance status
            $areaStats[$companyArea][$status]++;
        }

        // Prepare the output for areaData
        $areaData = [
            'labels' => array_keys($areaStats), // Area names as labels
            'datasets' => [
                [
                    'label' => 'Hadir',
                    'data' => array_column($areaStats, 'Hadir'), // Get 'Hadir' counts for each area
                    'backgroundColor' => '#2980b9', // Optionally add colors
                ],
                [
                    'label' => 'Tidak Hadir',
                    'data' => array_column($areaStats, 'Tidak Hadir'), // Get 'Tidak Hadir' counts for each area
                    'backgroundColor' => '#e74c3c', // Optionally add colors
                ],
                [
                    'label' => 'Belum Konfirmasi',
                    'data' => array_column($areaStats, 'Belum Konfirmasi'), // Get 'Belum Konfirmasi' counts for each area
                    'backgroundColor' => '#9aba57', // Optionally add colors
                ],
            ]
        ];

        return $areaData;
    }

    private function readRawFile($filename)
    {
        // Use base_path() to get the file path in the project root
        $filePath = base_path($filename);

        // Read the file content
        $jsonContent = file_get_contents($filePath);

        return $jsonContent;
    }
}
