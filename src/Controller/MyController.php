<?php
// src/Controller/MyController.php

namespace App\Controller;

use App\Service\TwilioService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MyController extends AbstractController
{
    /**
     * @Route("/send-sms")
     */
    public function sendSms(TwilioService $twilioService): Response
    {
        $to = '+21629330449'; // Replace with the phone number you want to send the SMS to
        $message = 'Hello, this is a test message from Twilio!'; // Replace with the message you want to send
        $twilioService->sendSms($to, $message);
        return new Response('SMS sent!');
    }
}