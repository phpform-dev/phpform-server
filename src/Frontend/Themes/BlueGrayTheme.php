<?php

namespace App\Frontend\Themes;

final class BlueGrayTheme extends AbstractTheme
{
    public function getPrimaryColor(): string
    {
        return '#263238';
    }

    public function getSecondaryColor(): string
    {
        return '#37474F';
    }

    public function getBackgroundColor(): string
    {
        return '#ECEFF1';
    }

    public function getErrorColor(): string
    {
        return '#990011';
    }
}