<?php

namespace App\Frontend\Themes;

final class PinkTheme extends AbstractTheme
{
    public function getPrimaryColor(): string
    {
        return '#880E4F';
    }

    public function getBackgroundColor(): string
    {
        return '#FCE4EC';
    }

    public function getErrorColor(): string
    {
        return '#990011';
    }

    public function getSecondaryColor(): string
    {
        return '#AD1457';
    }
}