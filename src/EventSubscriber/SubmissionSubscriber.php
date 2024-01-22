<?php
namespace App\EventSubscriber;

use App\Event\NewSubmissionEvent;
use App\Service\FormNotificationService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SubmissionSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly FormNotificationService $formNotificationService,
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
        $this->formNotificationService->sendAllByFormId($submission->getForm()->getId());
    }
}
