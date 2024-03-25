<?php

namespace App\Frontend\Themes;

final class IndigoTheme extends AbstractTheme
{
    public function getPrimaryColor(): string
    {
        return '#1A237E';
    }

    public function getSecondaryColor(): string
    {
        return '#283593';
    }

    public function getBackgroundColor(): string
    {
        return '#E8EAF6';
    }

    public function getErrorColor(): string
    {
        return '#990011';
    }
}