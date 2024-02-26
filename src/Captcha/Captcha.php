<?php
namespace App\Captcha;

use App\Captcha\Providers\HCaptchaProvider;
use App\Captcha\Providers\ReCaptchaProvider;

final class Captcha
{
  public const CAPTCHA_PROVIDER_RECAPTCHA = 0;
  public const CAPTCHA_PROVIDER_HCAPTCHA = 1;

  public function getProviders(): array
  {
    return [
      self::CAPTCHA_PROVIDER_RECAPTCHA => new ReCaptchaProvider(),
      self::CAPTCHA_PROVIDER_HCAPTCHA => new HCaptchaProvider(),
    ];
  }

    public function getProvider(int $provider): ?CaptchaProviderInterface
    {
        return $this->getProviders()[$provider] ?? null;
    }
}