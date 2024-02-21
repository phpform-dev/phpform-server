<?php

namespace App\Service;

use App\Entity\Form;

readonly class TokenProtectionService
{
    public function __construct(
        private Form $form,
    ) {
    }

    public function isTokenValid(string $token): bool
    {
        $parts = explode('.', $token);
        $salt = end($parts);
        $hash = substr($token, 0, -strlen($salt) - 1);

        if (str_starts_with($hash, 'Bearer ')) {
            $hash = substr($hash, 7);
        }

        return $this->createHash($salt) === $hash;
    }

    public function createHash(string $salt): string
    {
        return hash('sha256', $this->form->getSecret() . $salt);
    }

    public function createToken(string $salt): string
    {
        return $this->createHash($salt) . '.' . $salt;
    }
}