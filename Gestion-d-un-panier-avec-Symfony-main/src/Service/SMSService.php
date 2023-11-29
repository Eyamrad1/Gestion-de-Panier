<?php

// src/Service/SMSService.php

// src/Service/SMSService.php

// src/Service/SMSService.php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twilio\Rest\Client;

class SMSService
{
    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }

    public function sendSMS($phoneNumber, $message): void
    {
        $accountSid = $this->params->get('twilio_account_sid');
        $authToken = $this->params->get('twilio_auth_token');
        $twilioPhoneNumber = $this->params->get('twilio_phone_number');

        // Initialize Twilio client
        $twilio = new Client($accountSid, $authToken);

        // Use the Twilio client to send an SMS
        $twilio->messages->create(
            $phoneNumber, // To phone number
            [
                'from' => $twilioPhoneNumber, // Your Twilio phone number
                'body' => $message,
            ]
        );
    }
}
