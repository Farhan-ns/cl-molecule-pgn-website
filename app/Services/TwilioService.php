<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class TwilioService
{
    private $twilio_sid;
    private $twilio_token;
    private $twilio_from;
    private $twilio_client;

    public function __construct()
    {
        $this->twilio_sid = config('twilio.twilio_sid');
        $this->twilio_token = config('twilio.twilio_token');
        $this->twilio_from = config('twilio.twilio_from');

        if (empty($this->twilio_sid) || empty($this->twilio_token)) {
            throw new Exception('Twilio SID and token cannot be empty.');
        }

        $this->twilio_client = new Client($this->twilio_sid, $this->twilio_token);
    }

    public function sendContentMediaQR(string $recipientNumber, string $filename)
    {
        return $this->twilio_client->messages->create(
            "whatsapp:$recipientNumber",
            [
                'contentSid' => 'HX36b34334e907f11a6007114d0938ce62', // From Content Template Builder
                'contentVariables' => json_encode([
                    '1' => $filename,
                ]),
                'from' => "whatsapp:$this->twilio_from",
                'messagingServiceSid' => "MG8717212bab84e65d49c67c227d6d8a08", // From Senders->Services
            ]
        );
    }

    private function getCurrentTimeText()
    {
        $currentHour = now()->setTimezone('Asia/Jakarta')->hour;

        // Determine the time text based on the current hour
        if ($currentHour >= 5 && $currentHour < 11) {
            $timeText = 'Pagi';
        } elseif ($currentHour >= 11 && $currentHour < 15) {
            $timeText = 'Siang';
        } elseif ($currentHour >= 15 && $currentHour < 19) {
            $timeText = 'Sore';
        } else {
            $timeText = 'Malam';
        }

        return $timeText;
    }
}