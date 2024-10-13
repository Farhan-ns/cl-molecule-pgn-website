<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WatzapService
{
    private $BASE_URL = 'https://api.watzap.id/v1';
    private $ENDPOINTS = [
        'send_message' => '/send_message',
        'send_message_image' => '/send_image_url',
    ];

    private $watzapApiKey;
    private $watzapNumberKey;

    public function __construct()
    {
        $this->watzapApiKey = config('watzap.watzap_api_key');
        $this->watzapNumberKey = config('watzap.watzap_number_key');

        if (empty($this->watzapApiKey) || empty($this->watzapNumberKey)) {
            throw new Exception('Watzap API Key & Number Key cannot be empty.');
        }
    }

    /**
     * Send Text Message to Recipient
     *
     * @param string $recipientNumber recipient number to send message for
     * @param string $message text or caption
     * @return string
     */
    public function sendMessage($recipientNumber, $message) : string
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->BASE_URL . $this->ENDPOINTS['send_message'], [
            'api_key' => $this->watzapApiKey,
            'number_key' => $this->watzapNumberKey,
            'phone_no' => $recipientNumber,
            'message' => $message,
            'wait_until_send' => 1,
        ]);

        return $response->body();
    }

    /**
     * Send Message to Recipient along with image
     *
     * @param string $recipientNumber recipient number to send message for
     * @param string $imageUrl publicly accessible image url 
     * @param string $message text or caption
     * @param integer $separateCaption if true, will separate image and message into two message
     * @return string
     */
    public function sendMessageWithImage(
        $recipientNumber,
        $imageUrl,
        $message,
        $separateCaption = 0
    ) : string {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->timeout(60)->post($this->BASE_URL . $this->ENDPOINTS['send_message_image'], [
            'api_key' => $this->watzapApiKey,
            'number_key' => $this->watzapNumberKey,
            'phone_no' => $recipientNumber,
            'message' => $message,
            'url' => $imageUrl,
            'separate_caption' => $separateCaption,
            'wait_until_send' => 1,
        ]);

        return $response->body();
    }
}
