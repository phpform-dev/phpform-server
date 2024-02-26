<?php
namespace App\Captcha;

interface CaptchaProviderInterface
{
    public function validate(string $response, string $secretKey, ?string $userIp = null): bool;

    public function getName(): string;

    public function getHomePageUrl(): string;

    public function getDocumentationUrl(): string;
}