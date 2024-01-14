<?php
namespace App\Service;

class ReCaptchaService
{
    private string $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';

    public function validate(string $recaptchaResponse, string $secretKey, ?string $userIp = null): bool
    {
        if (empty($recaptchaResponse)) {
            return false;
        }

        $data = [
            'secret' => $secretKey,
            'response' => $recaptchaResponse,
            'remoteip' => $userIp
        ];

        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];

        $context = stream_context_create($options);
        $response = file_get_contents($this->verifyUrl, false, $context);
        if ($response === false) {
            return false;
        }

        $result = json_decode($response);
        return $result->success;
    }
}
