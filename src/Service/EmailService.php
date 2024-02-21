<?php
namespace App\Service;

use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

class EmailService
{
    public function __construct(
        private readonly ConfigService $configService,
    )
    {
    }

    private ?string $lastSendEmailError = null;

    public function sendEmail(string $dsn, Email $email): bool
    {
        $transport = Transport::fromDsn($dsn);
        $mailer = new Mailer($transport);

        try {
            $mailer->send($email);
            return true;
        } catch (\Throwable $e) {
            $this->lastSendEmailError = $e->getMessage();
            return false;
        }
    }

    public function buildSmtpDsn(string $host, int $port, string $username, string $password, string $encryption): string
    {
        return sprintf('smtp://%s:%s@%s:%d?encryption=%s', urlencode($username), urlencode($password), $host, $port, $encryption);
    }

    public function buildSmtpDsnFromConfig(): string {
        $host = $this->configService->get(ConfigService::SMTP_HOST);
        $port = $this->configService->get(ConfigService::SMTP_PORT);
        $username = $this->configService->get(ConfigService::SMTP_USERNAME);
        $password = $this->configService->get(ConfigService::SMTP_PASSWORD);
        $encryption = $this->configService->get(ConfigService::SMTP_ENCRYPTION);

        return $this->buildSmtpDsn($host, $port, $username, $password, $encryption);
    }

    public function getLastSendEmailError(): ?string
    {
        return $this->lastSendEmailError;
    }
}