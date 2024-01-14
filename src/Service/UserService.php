<?php
namespace App\Service;

use App\Entity\User;
use App\Entity\UserPermission;
use App\Repository\UserPermissionRepository;
use App\Repository\UserRepository;

readonly class UserService
{
    public function __construct(
        private UserRepository              $userRepository,
        private UserPermissionRepository    $userPermissionRepository,
        private FormService                 $formService,
    )
    {
    }

    public function getCount(): int
    {
        return $this->userRepository->countAll();
    }

    public function create(User $user): void
    {
        $this->userRepository->save($user);
    }

    public function addAccessToForms(User $user, array $formIds = []): void
    {
        if ($user->getIsSuperuser()) {
            return;
        }

        if (empty($formIds)) {
            return;
        }

        $forms = $this->formService->getByIds($formIds);
        foreach ($forms as $form) {
            $userPermission = new UserPermission();
            $userPermission->setUser($user);
            $userPermission->setForm($form);
            $this->userPermissionRepository->save($userPermission);
        }
    }

    public function replaceAccessToForms(User $user, array $formIds = []): void
    {
        $this->userPermissionRepository->removeByUserId($user->getId());
        $this->addAccessToForms($user, $formIds);
    }

    public function getAll(): array
    {
        return $this->userRepository->findAll();
    }

    public function getById(int $id): User
    {
        return $this->userRepository->find($id);
    }

    public function deleteById(int $userId): void
    {
        $this->userPermissionRepository->removeByUserId($userId);
        $this->userRepository->removeById($userId);
    }

    public function edit(User $user): void
    {
        $this->userRepository->save($user);
    }
}
