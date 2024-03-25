<?php

namespace App\Frontend\Themes;

final class BlueTheme extends AbstractTheme
{
    public function getPrimaryColor(): string
    {
        return '#0D47A1';
    }

    public function getSecondaryColor(): string
    {
        return '#1565C0';
    }

    public function getBackgroundColor(): string
    {
        return '#E3F2FD';
    }

    public function getErrorColor(): string
    {
        return '#990011';
    }
}