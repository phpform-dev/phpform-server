<?php
namespace App\Service;

use App\Entity\FormField;
use App\Repository\FormFieldRepository;
use App\Repository\FormRepository;

class FormFieldService {
    public function __construct(
        private readonly FormFieldRepository $formFieldRepository,
        private readonly FormRepository $formRepository,
    )
    {
    }

    public function create(
        int $formId,
        string $type,
        string $name,
        string $label,
        ?string $hint,
        bool $isRequired,
        array $validations,
    ): ?FormField
    {
        $form = $this->formRepository->find($formId);
        if ($form === null) {
            return null;
        }

        // Make sure the name is unique
        $existingFormField = $this->formFieldRepository->findOneBy([
            'form' => $form,
            'name' => $name,
        ]);
        if ($existingFormField !== null) {
            return null;
        }

        // Getting the latest position
        $latestFormField = $this->formFieldRepository->findOneBy([
            'form' => $form,
        ], [
            'position' => 'DESC',
        ]);
        $position = $latestFormField !== null ? $latestFormField->getPosition() + 1 : 0;

        $formField = new FormField();
        $formField->setForm($form);
        $formField->setType($type);
        $formField->setName($name);
        $formField->setLabel($label);
        $formField->setHint($hint);
        $formField->setPosition($position);
        $formField->setIsRequired($isRequired);
        $formField->setValidations($validations);

        $this->formFieldRepository->save($formField);

        return $formField;
    }

    public function edit(
        int $id,
        string $type,
        string $name,
        string $label,
        ?string $hint,
        bool $isRequired,
        array $validations,
    ): ?FormField
    {
        $formField = $this->formFieldRepository->find($id);
        if ($formField === null) {
            return null;
        }

        $existingFormField = $this->formFieldRepository->findOneBy([
            'form' => $formField->getForm(),
            'name' => $name,
        ]);
        if ($existingFormField !== null && $existingFormField->getId() !== $id) {
            return null;
        }

        $formField->setType($type);
        $formField->setName($name);
        $formField->setLabel($label);
        $formField->setHint($hint);
        $formField->setIsRequired($isRequired);
        $formField->setValidations($validations);

        $this->formFieldRepository->save($formField);

        return $formField;
    }

    public function getAllByFormId(int $formId): array
    {
        return $this->formFieldRepository->findBy([
            'form' => $formId,
        ], [
            'position' => 'ASC',
        ]);
    }

    public function getById(int $id): ?FormField
    {
        return $this->formFieldRepository->find($id);
    }

    public function deleteById(int $id): void
    {
        $this->formFieldRepository->deleteById($id);
    }

    public function moveUp(int $id): void
    {
        $this->formFieldRepository->moveById($id, 'up');
    }

    public function moveDown(int $id): void
    {
        $this->formFieldRepository->moveById($id, 'down');
    }

    public function getCountByFormId(int $formId): int
    {
        return $this->formFieldRepository->countByFormId($formId);
    }
}