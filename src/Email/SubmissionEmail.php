<?php

namespace App\Email;

use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class SubmissionEmail
{
    static public function invoke(string $to, string $from, string $fromName, string $formName, int $newSubmissionCount): Email
    {
        return (new Email())
            ->from(new Address($from, $fromName))
            ->to($to)
            ->subject(sprintf('New submission on %s', $formName))
            ->text(sprintf("Someone submitted a new response to the %s.\nThere are %d new submissions waiting for you.", $formName, $newSubmissionCount));
    }
}
