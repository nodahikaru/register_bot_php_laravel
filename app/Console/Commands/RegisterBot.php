<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;

class RegisterBot extends Command
{

    protected $signature = 'bot:register';
    protected $description = 'Automate the registration process';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $client =  new Client();
        $recaptchToken = $this->getRecaptchaToken();

        $randomEmail = $this->generateRandomEmail();

        $response = $client->post('http://localhost:8000/register', [
            'form_params' => [
                'name' => 'Bot User',
                'email' => $randomEmail,
                'password' => 'password',
                'g-recapatch-response' => $recaptchToken
            ]
        ]);

        if ($response->getStatusCode() == 200)
        {
            $this->info('Registration form submitted');
        } else 
        {
            $this->error('Failed to submit');
        }

        $verificationCode = $this->getVerificationCodeFromEmail();

        $response = $client->post('http://localhost:8000/verify-email', [
            'form_params' => [
                'email' => $randomEmail,
                'verification_code' => $verificationCode
            ]
        ]);

        if ($response->getStatusCode() == 200)
        {
            $this->info('Email verified');
        } else 
        {
            $this->error('Failed to verify');
        }
    }

    private function getRecaptchaToken()
    {
        return 'dummy';
    }

    private function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $caractersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++)
        {
            $randomString .= $characters[rand(0, $caractersLength -1)];
        }
        return $randomString;
    }

    private function generateRandomEmail($length = 10)
    {
        $domains = ['example.com', 'test.com', 'mail.com', 'random.org'];
        $randomString = $this->generateRandomString($length);
        $randomDomain = $domains[array_rand($domains)];
        return $randomString . '@' . $randomDomain;
    }

    private function getVerificationCodeFromEmail()
    {
        return '999999';
    }
}
