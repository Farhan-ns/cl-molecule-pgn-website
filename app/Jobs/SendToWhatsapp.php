<?php

namespace App\Jobs;

use App\Models\Registration;
use App\Models\TwilioLog;
use App\Services\PhoneNumberParserService;
use App\Services\PublicPathService;
use App\Services\TwilioService;
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
    public function handle(TwilioService $twilio, PublicPathService $pathService): void
    {

        $recipientNumber = $this->registration->phone;
        $recipientNumber = PhoneNumberParserService::parseToInternational($recipientNumber);

        $response = $twilio->sendContentMediaQR($recipientNumber, $this->fileName);

        TwilioLog::create([
            'response_log' => json_encode($response->toArray()),
            'registration_id' => $this->registration->id,
        ]);
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        TwilioLog::create([
            'response_log' => json_encode([
                'Sending Whatsapp Message has failed'
            ]),
            'registration_id' => $this->registration->id,
        ]);
    }
}
