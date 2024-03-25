<?php

namespace App\Frontend\Themes;

final class GreenTheme extends AbstractTheme
{
    public function getPrimaryColor(): string
    {
        return '#1B5E20';
    }

    public function getSecondaryColor(): string
    {
        return '#2E7D32';
    }

    public function getBackgroundColor(): string
    {
        return '#E8F5E9';
    }

    public function getErrorColor(): string
    {
        return '#990011';
    }
}