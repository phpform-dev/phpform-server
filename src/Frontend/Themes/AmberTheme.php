<?php

namespace App\Frontend\Themes;

final class AmberTheme extends AbstractTheme
{
    public function getPrimaryColor(): string
    {
        return '#FF6F00';
    }

    public function getSecondaryColor(): string
    {
        return '#FF8F00';
    }

    public function getBackgroundColor(): string
    {
        return '#FFF8E1';
    }

    public function getErrorColor(): string
    {
        return '#990011';
    }
}