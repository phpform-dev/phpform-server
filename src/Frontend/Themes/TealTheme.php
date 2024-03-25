<?php

namespace App\Frontend\Themes;

final class TealTheme extends AbstractTheme
{
    public function getPrimaryColor(): string
    {
        return '#004D40';
    }

    public function getSecondaryColor(): string
    {
        return '#00695C';
    }

    public function getBackgroundColor(): string
    {
        return '#E0F2F1';
    }

    public function getErrorColor(): string
    {
        return '#990011';
    }
}