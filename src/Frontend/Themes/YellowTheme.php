<?php

namespace App\Frontend\Themes;

final class YellowTheme extends AbstractTheme
{
    public function getPrimaryColor(): string
    {
        return '#F57F17';
    }

    public function getSecondaryColor(): string
    {
        return '#F9A825';
    }

    public function getBackgroundColor(): string
    {
        return '#FFFDE7';
    }

    public function getErrorColor(): string
    {
        return '#990011';
    }
}