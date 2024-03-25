<?php

namespace App\Frontend\Themes;

final class CyanTheme extends AbstractTheme
{
    public function getPrimaryColor(): string
    {
        return '#006064';
    }

    public function getSecondaryColor(): string
    {
        return '#00838F';
    }

    public function getBackgroundColor(): string
    {
        return '#E0F7FA';
    }

    public function getErrorColor(): string
    {
        return '#990011';
    }
}