<?php

namespace App\Frontend\Themes;

final class DeepOrangeTheme extends AbstractTheme
{
    public function getPrimaryColor(): string
    {
        return '#BF360C';
    }

    public function getSecondaryColor(): string
    {
        return '#D84315';
    }

    public function getBackgroundColor(): string
    {
        return '#FBE9E7';
    }

    public function getErrorColor(): string
    {
        return '#990011';
    }
}