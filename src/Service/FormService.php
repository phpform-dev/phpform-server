<?php
namespace App\Service;

use App\Entity\Form;
use App\Entity\User;
use App\Repository\FormRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

readonly class FormService
{
    public function __construct(private FormRepository $formRepository)
    {
    }

    public function create(Form $form): void
    {
        $this->formRepository->save($form);
    }

    public function edit(Form $form): void
    {
        $this->formRepository->save($form);
    }

    public function getAll(): array
    {
        return $this->formRepository->findAll();
    }

    public function getByIds(array $ids): array
    {
        return $this->formRepository->findByIds($ids);
    }

    public function archive(Form $form): void
    {
        $this->formRepository->archive($form);
    }

    public function recover(Form $form): void
    {
        $this->formRepository->recover($form);
    }

    public function getCountsForUser(User $user): array
    {
        if ($user->getIsSuperuser()) {
            return [
                'active' => $this->formRepository->countActive(),
                'archived' => $this->formRepository->countArchived(),
            ];
        }

        $permissions = $user->getPermissions();
        if (count($permissions) === 0) {
            return [
                'active' => 0,
                'archived' => 0,
            ];
        }

        $counts = [
            'active' => 0,
            'archived' => 0,
        ];

        foreach ($permissions as $permission) {
            if ($permission->getForm()->getDeletedAt() === null) {
                $counts['active']++;
            } else {
                $counts['archived']++;
            }
        }

        return $counts;
    }

    public function getById(int $id): Form
    {
        return $this->formRepository->find($id);
    }

    public function getByHash(string $hash): ?Form
    {
        return $this->formRepository->findOneBy(['hash' => $hash]);
    }

    public function find(?array $ids = null, bool $archived = false): array
    {
        return $this->formRepository->findByIdsAndStatus($ids, $archived);
    }
}