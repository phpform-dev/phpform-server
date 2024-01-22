<?php
namespace App\Service;

use App\Entity\Config;
use App\Repository\ConfigRepository;

class ConfigService
{
    const VAPID_PUBLIC_KEY = 'vapid_public_key';
    const VAPID_PRIVATE_KEY = 'vapid_private_key';

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
}
