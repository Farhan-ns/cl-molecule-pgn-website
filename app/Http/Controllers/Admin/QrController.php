<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Services\PublicPathService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrController extends Controller
{
    public function downloadQr(Request $request, Registration $registration)
    {
        $uniqueCode = $registration->getUniqueCode();
        
        return response()->streamDownload(
            function () use ($uniqueCode) {
                echo QrCode::size(1000)
                    ->format('png')
                    ->margin(1)
                    ->errorCorrection('M')
                    ->generate($uniqueCode);
            },
            "$registration->name.png",
            ['Content-Type' => 'image/png'],
        );
    }

    public function previewQr(Request $request, Registration $registration)
    {
        $pathService = new PublicPathService();

        $uniqueCode = $registration->getUniqueCode();
        
        $format = 'png';
        $directory = $pathService->getQrPath() . "/$registration->id";
        $fileName = "$registration->email.$format";

        File::ensureDirectoryExists($directory);

        QrCode::format($format)
            ->size(1000)
            ->errorCorrection('M')
            ->generate($uniqueCode, "$directory/$fileName");
        
        return asset("qr/$registration->id/$fileName");
        // return response()->file("$directory/$fileName");
    }


}
