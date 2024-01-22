<?php
namespace App\Service;

use App\Entity\User;
use App\Entity\UserBrowserToken;
use App\Repository\UserBrowserTokenRepository;

class UserBrowserTokenService {
    public function __construct(
        private readonly UserBrowserTokenRepository $userBrowserTokenRepository,
    )
    {
    }

    public function saveBrowserTokenByUser(User $user, string $endpoint, string $publicKey, string $authToken): UserBrowserToken
    {
        $entity = $this->userBrowserTokenRepository->findOneBy([
            'user' => $user,
            'endpoint' => $endpoint,
        ]);

        if (empty($entity)) {
            $entity = $this->userBrowserTokenRepository->createForUser($user, $endpoint, $publicKey, $authToken);
        }

        return $entity;
    }

    public function getAllByUserId(int $userId): array
    {
        return $this->userBrowserTokenRepository->findBy([
            'user' => $userId,
        ]);
    }

    public function deleteByEndpoint(string $token): void
    {
        $this->userBrowserTokenRepository->deleteByEndpoint($token);
    }
}
