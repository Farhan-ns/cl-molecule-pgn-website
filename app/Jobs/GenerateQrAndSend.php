<?php

namespace App\Jobs;

use App\Models\Registration;
use App\Services\PublicPathService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Str;

class GenerateQrAndSend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private Registration $registration;

    /**
     * Create a new job instance.
     */
    public function __construct(Registration $registration)
    {
        $this->registration = $registration;
    }

    /**
     * Execute the job.
     */
    public function handle(PublicPathService $pathService): void
    {
        $registration = $this->registration;

        $encryptedId = Crypt::encrypt($registration->id);

        $format = 'png';
        $directory = $pathService->getQrPath();
        $name = Str::kebab($registration->name);
        $fileName = "$name-$registration->email.$format";
        $nameWithDiretory = "$directory/$fileName";

        File::ensureDirectoryExists($directory);

        QrCode::format($format)
            ->size(1000)
            ->margin(1)
            ->errorCorrection('M')
            ->generate($encryptedId, $nameWithDiretory);

        SendToWhatsapp::dispatch($registration, "qr/$fileName", $pathService);
    }
}
