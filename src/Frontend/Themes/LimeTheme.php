<?php

namespace App\Frontend\Themes;

final class LimeTheme extends AbstractTheme
{
    public function getPrimaryColor(): string
    {
        return '#827717';
    }

    public function getSecondaryColor(): string
    {
        return '#9E9D24';
    }

    public function getBackgroundColor(): string
    {
        return '#F9FBE7';
    }

    public function getErrorColor(): string
    {
        return '#990011';
    }
}