<?php
namespace App\Service;

use App\Entity\Form;
use App\Entity\FormNotificationConfig;
use App\Entity\User;
use App\Repository\FormNotificationConfigRepository;

class FormNotificationConfigService
{
    public function __construct(
        private readonly FormNotificationConfigRepository $formNotificationConfigRepository,
    )
    {
    }

    public function getByFormAndUser(Form $form, User $user): ?FormNotificationConfig
    {
        return $this->formNotificationConfigRepository->findOneBy([
            'form' => $form,
            'user' => $user,
        ]);
    }

    public function getAllByFormId(int $formId): array
    {
        return $this->formNotificationConfigRepository->findBy([
            'form' => $formId,
        ]);
    }

    public function save(FormNotificationConfig $formNotificationConfig): FormNotificationConfig
    {
        return $this->formNotificationConfigRepository->save($formNotificationConfig);
    }

    public function create(Form $form, User $user): FormNotificationConfig
    {
        $formNotificationConfig = new FormNotificationConfig();
        $formNotificationConfig->setForm($form);
        $formNotificationConfig->setUser($user);
        $formNotificationConfig->setIsBrowserPushEnabled(false);
        $formNotificationConfig->setIsEmailEnabled(false);
        $formNotificationConfig->setLastNotificationSentAt((new \DateTimeImmutable())->sub(new \DateInterval('P1D')));
        $this->formNotificationConfigRepository->save($formNotificationConfig);

        return $formNotificationConfig;
    }
}