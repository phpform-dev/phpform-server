<?php
namespace App\EventSubscriber;

use App\Event\NewSubmissionEvent;
use App\Service\FormNotificationService;
use App\Service\FormWebhookService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SubmissionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly FormNotificationService $formNotificationService,
        private readonly FormWebhookService $formWebhookService,
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'submission.new' => 'onNewSubmission',
        ];
    }

    public function onNewSubmission(NewSubmissionEvent $event): void
    {
        $submission = $event->getSubmission();
        $form = $submission->getForm();
        $this->formNotificationService->sendAllByFormId($form->getId());
        $this->formWebhookService->callAllWebhooks($form, $submission);
    }
}
