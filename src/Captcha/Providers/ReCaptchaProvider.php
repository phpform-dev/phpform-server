<?php
namespace App\Captcha\Providers;

use App\Captcha\CaptchaProviderInterface;

class ReCaptchaProvider implements CaptchaProviderInterface
{
    private string $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';

    public function validate(string $response, string $secretKey, ?string $userIp = null): bool
    {
        if (empty($response)) {
            return false;
        }

        $data = [
            'secret' => $secretKey,
            'response' => $response,
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

    public function getName(): string
    {
        return 'reCaptcha';
    }

    public function getHomePageUrl(): string
    {
        return 'https://www.google.com/recaptcha/about/';
    }

    public function getDocumentationUrl(): string
    {
        return 'https://developers.google.com/recaptcha/docs/v3';
    }
}
