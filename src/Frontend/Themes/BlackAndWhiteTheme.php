<?php

namespace App\Frontend\Themes;

final class BlackAndWhiteTheme extends AbstractTheme
{
    public function getPrimaryColor(): string
    {
        return '#000000';
    }

    public function getSecondaryColor(): string
    {
        return '#000000';
    }

    public function getBackgroundColor(): string
    {
        return '#FFFFFF';
    }

    public function getErrorColor(): string
    {
        return '#990011';
    }
}