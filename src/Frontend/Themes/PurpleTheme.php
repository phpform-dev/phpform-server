<?php

namespace App\Frontend\Themes;

final class PurpleTheme extends AbstractTheme
{
    public function getPrimaryColor(): string
    {
        return '#4A148C';
    }


    public function getBackgroundColor(): string
    {
        return '#F3E5F5';
    }

    public function getErrorColor(): string
    {
        return '#990011';
    }

    public function getSecondaryColor(): string
    {
        return '#6A1B9A';
    }
}