<?php
namespace App\Event;

use App\Entity\Submission;
use Symfony\Contracts\EventDispatcher\Event;

class NewSubmissionEvent extends Event
{
    public function __construct(
        private readonly Submission $submission,
    ) {
    }

    public function getSubmission(): Submission
    {
        return $this->submission;
    }
}