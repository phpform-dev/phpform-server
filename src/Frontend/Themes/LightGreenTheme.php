<?php

namespace App\Frontend\Themes;

final class LightGreenTheme extends AbstractTheme
{
    public function getPrimaryColor(): string
    {
        return '#33691E';
    }

    public function getSecondaryColor(): string
    {
        return '#558B2F';
    }

    public function getBackgroundColor(): string
    {
        return '#F1F8E9';
    }

    public function getErrorColor(): string
    {
        return '#990011';
    }
}