<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Notifications\RegistrationScanned;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ScanController extends Controller
{
    public function parseScan(Request $request)
    {
        $request->validate([
            'qr_value' => 'required'
        ]);

        $qrValue = $request->qr_value;
        $admin = $request->user();
        $registration = Registration::where('unique_code', $qrValue)->firstOrFail();

        if ($registration->has_attended) {
            return response()->error([], 400, 'QR telah di-scan sebelumnya');
        }

        DB::transaction(function () use($registration, $admin) {
            $registration->has_attended = true;
            $registration->attended_at = now();
            $registration->save();

            $admin->notify(new RegistrationScanned($registration));
        });

        return response()->success($registration, 200, 'Scan data berhasil', );
    }

    // Second scan is a sub event during the whole event
    public function parseSecondScan(Request $request)
    {
        $request->validate([
            'qr_value' => 'required'
        ]);

        $decryptedValue = Crypt::decrypt($request->qr_value);
        $registration = Registration::find($decryptedValue);

        DB::transaction(function () use($registration) {
            $registration->has_second_scan = true;
            $registration->second_scan_at = now();
            $registration->save();
        });

        return response()->success($registration, 200, 'Scan tahap 2 berhasil', );
    }
}
