<?php

namespace App\Frontend\Themes;

final class ThemesProvider
{
    public function getThemes(): array
    {
        return [
            new AmberTheme(),
            new BlackAndWhiteTheme(),
            new BlueTheme(),
            new BrownTheme(),
            new CyanTheme(),
            new DeepOrangeTheme(),
            new GrayTheme(),
            new GreenTheme(),
            new IndigoTheme(),
            new LightGreenTheme(),
            new LimeTheme(),
            new OrangeTheme(),
            new PinkTheme(),
            new PurpleTheme(),
            new TealTheme(),
            new YellowTheme(),
        ];
    }

    public function getTheme(string $themeName): AbstractTheme
    {
        $themes = $this->getThemes();

        foreach ($themes as $theme) {
            if ($theme->getName() === $themeName) {
                return $theme;
            }
        }

        throw new \Exception('Theme not found');
    }
}