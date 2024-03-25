<?php

namespace App\Frontend\Themes;

final class GrayTheme extends AbstractTheme
{
    public function getPrimaryColor(): string
    {
        return '#212121';
    }

    public function getSecondaryColor(): string
    {
        return '#424242';
    }

    public function getBackgroundColor(): string
    {
        return '#FAFAFA';
    }

    public function getErrorColor(): string
    {
        return '#990011';
    }
}