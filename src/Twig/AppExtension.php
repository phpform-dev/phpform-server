<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use DateTime;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('beautify_datetime', [$this, 'beautifyDateTime']),
        ];
    }

    public function beautifyDateTime($datetime): string
    {
        $now = new DateTime();
        $datetime = new DateTime($datetime);
        $interval = $now->diff($datetime);

        if ($interval->days > 14) {
            return $datetime->format('d M Y H:i');
        } elseif ($interval->days > 0) {
            return $interval->days . ' days ago';
        } elseif ($interval->h > 0) {
            return $interval->h . ' hours and ' . $interval->i . ' minutes ago';
        } elseif ($interval->i > 0) {
            return $interval->i . ' minutes ago';
        } else {
            return 'less than a minute ago';
        }
    }
}