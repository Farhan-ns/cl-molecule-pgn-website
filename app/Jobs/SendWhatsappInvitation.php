<?php

namespace App\Jobs;

use App\Models\Blast;
use App\Models\BlastLog;
use App\Models\TwilioLog;
use App\Services\PhoneNumberParserService;
use App\Services\TwilioService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class SendWhatsappInvitation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $blast;

    /**
     * Create a new job instance.
     */
    public function __construct(Blast $blast)
    {
        $this->blast = $blast;
    }

    /**
     * Execute the job.
     */
    public function handle(TwilioService $twilio): void
    {
        $recipient = $this->blast->phone_number;
        $recipient = PhoneNumberParserService::parseToInternational($recipient);

        $response = $twilio->sendContentMediaInvitationPart1($recipient);
        $response2 = null;
        if (empty($response->errorCode)) {
            sleep(4);
            $response2 = $twilio->sendContentMediaInvitationPart2($recipient);
        }

        BlastLog::create([
            'response_log' => json_encode($response->toArray()),
            'blast_id' => $this->blast->id,
        ]);
    }

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        BlastLog::create([
            'response_log' => json_encode([
                'Sending Whatsapp Invitation has failed'
            ]),
            'blast_id' => $this->blast->id,
        ]);
    }
}
