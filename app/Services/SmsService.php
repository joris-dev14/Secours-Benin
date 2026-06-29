<?php

namespace App\Services;

use AfricasTalking\SDK\AfricasTalking;
use Illuminate\Support\Facades\Log;

class SmsService
{
    private $sms;

    public function __construct()
    {
        $AT = new AfricasTalking(
            config('services.africastalking.username'),
            config('services.africastalking.api_key')
        );

        $this->sms = $AT->sms();
    }

    public function send(string $phone, string $message): bool
    {
        try {
            $result = $this->sms->send([
                'to'      => $phone,   // format international : +22961000000
                'message' => $message,
            ]);

            return $result['status'] === 'success';

        } catch (\Exception $e) {
            Log::error('SMS OTP error: ' . $e->getMessage());
            return false;
        }
    }
}