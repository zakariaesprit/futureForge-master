<?php

namespace App\Service;

use Twilio\Rest\Client;

class TwilioSmsService
{
    private $accountSid;
    private $authToken;
    private $twilioPhoneNumber;

    public function __construct(string $accountSid, string $authToken, string $twilioPhoneNumber)
    {
        $this->accountSid = $accountSid;
        $this->authToken = $authToken;
        $this->twilioPhoneNumber = $twilioPhoneNumber;
    }

    public function sendSms(string $message, string $phoneNumber)
    {
        $client = new Client($this->accountSid, $this->authToken);

        $client->messages->create(
            $phoneNumber,
            [
                'from' => $this->twilioPhoneNumber,
                'body' => $message,
            ]
        );
    }
}
