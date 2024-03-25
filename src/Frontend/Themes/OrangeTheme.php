<?php

namespace App\Frontend\Themes;

final class OrangeTheme extends AbstractTheme
{
    public function getPrimaryColor(): string
    {
        return '#E65100';
    }

    public function getSecondaryColor(): string
    {
        return '#EF6C00';
    }

    public function getBackgroundColor(): string
    {
        return '#FFF8E1';
    }

    public function getErrorColor(): string
    {
        return '#FFF3E0';
    }
}