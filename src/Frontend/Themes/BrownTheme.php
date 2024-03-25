<?php

namespace App\Frontend\Themes;

final class BrownTheme extends AbstractTheme
{
    public function getPrimaryColor(): string
    {
        return '#3E2723';
    }

    public function getSecondaryColor(): string
    {
        return '#4E342E';
    }

    public function getBackgroundColor(): string
    {
        return '#EFEBE9';
    }

    public function getErrorColor(): string
    {
        return '#990011';
    }
}