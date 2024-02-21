<?php
namespace App\Service;

use App\Entity\Config;
use App\Repository\ConfigRepository;

class ConfigService
{
    const VAPID_PUBLIC_KEY = 'vapid_public_key';
    const VAPID_PRIVATE_KEY = 'vapid_private_key';
    const SMTP_HOST = 'smtp_host';
    const SMTP_PORT = 'smtp_port';
    const SMTP_USERNAME = 'smtp_username';
    const SMTP_PASSWORD = 'smtp_password';
    const SMTP_ENCRYPTION = 'smtp_encryption';
    const SMTP_FROM_EMAIL = 'smtp_from_email';
    const SMTP_FROM_NAME = 'smtp_from_name';

    public function __construct(
        private readonly ConfigRepository $configRepository,
    )
    {
    }

    public function get(string $id): ?string
    {
        $record = $this->configRepository->find($id);
        if ($record === null) {
            return null;
        }

        return $record->getValue();
    }

    public function set(string $id, string $value): void
    {
        $config = $this->configRepository->find($id);
        if ($config === null) {
            $config = new Config();
            $config->setId($id);
        }
        $config->setValue($value);
        $this->configRepository->save($config);
    }

    public function getMany(array $ids): array
    {
        $entities = $this->configRepository->findBy(['id' => $ids]);
        $entitiesById = [];
        foreach ($entities as $entity) {
            $entitiesById[$entity->getId()] = $entity->getValue();
        }

        return $entitiesById;
    }

    public function isBrowserPushNotificationsEnabled(): bool
    {
        $publicKey = $this->get(self::VAPID_PUBLIC_KEY);
        $privateKey = $this->get(self::VAPID_PRIVATE_KEY);

        return !empty($publicKey) && !empty($privateKey);
    }

    public function isSmtpEnabled(): bool
    {
        $host = $this->get(self::SMTP_HOST);
        $port = $this->get(self::SMTP_PORT);
        $username = $this->get(self::SMTP_USERNAME);
        $password = $this->get(self::SMTP_PASSWORD);
        $encryption = $this->get(self::SMTP_ENCRYPTION);
        $fromEmail = $this->get(self::SMTP_FROM_EMAIL);
        $fromName = $this->get(self::SMTP_FROM_NAME);

        return !empty($host) && !empty($port) && !empty($username) && !empty($password) && !empty($encryption) && !empty($fromEmail) && !empty($fromName);
    }
}
