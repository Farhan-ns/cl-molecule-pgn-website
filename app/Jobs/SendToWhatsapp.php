<?php

namespace App\Jobs;

use App\Models\Registration;
use App\Models\WatzapLog;
use App\Services\PhoneNumberParserService;
use App\Services\PublicPathService;
use App\Services\WatzapService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class SendToWhatsapp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $registration;
    private $fileName;

    /**
     * Create a new job instance.
     */
    public function __construct(Registration $registration, string $fileName, PublicPathService $pathService)
    {
        $this->registration = $registration;
        $this->fileName = $fileName;
    }

    /**
     * Execute the job.
     */
    public function handle(WatzapService $watzap, PublicPathService $pathService): void
    {
        $recipientNumber = $this->registration->phone;
        $recipientName = $this->registration->name;
        $recipientNumber = PhoneNumberParserService::parseToInternational($recipientNumber);

        $message = <<<END
        Terima kasih telah melakukan registrasi untuk acara Customer Business Forum Tahun 2024
        \"Bridging Challenges, Strengthening Bonds\"

        Yang akan dilaksanakan pada :
        Hari/Tanggal 	: Rabu/16 Oktober 2024
        Waktu		: 08.00 - 13.00
        Tempat		: ICE BSD - Nusantara Hall Ground Floor
        Maps		: https://maps.app.goo.gl/BdRhjvCP8wufZd4PA
        Dress Code : 
        * Pria - Kemeja batik lengan panjang
        * Wanita - Nuansa batik

        Harap menyimpan dan menunjukkan QR code diatas pada saat registrasi ulang di venue

        *****

        Undangan ini berlaku untuk 1 (satu) orang

        Download aplikasi My Pertamina melalui tautan berikut :
        Google Play Store - 
        https://play.google.com/store/apps/details?id=com.dafturn.mypertamina&pcampaignid=web_share

        Apple Store -
        https://apps.apple.com/id/app/mypertamina/id1295039064

        Sampai jumpa di Customer Business Forum Tahun 2024

        Regards,
        Management
        PT Perusahaan Gas Negara Tbk.
        END;

        $response = $watzap->sendMessageWithImage($recipientNumber, asset($this->fileName), $message);
        // $response = $watzap->sendMessage($recipientNumber, $message);

        WatzapLog::create([
            'response_log' => json_encode($response),
            'registration_id' => $this->registration->id,
        ]);
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        WatzapLog::create([
            'response_log' => json_encode([
                'Sending Whatsapp Message has failed'
            ]),
            'registration_id' => $this->registration->id,
        ]);
    }
}
