<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Utility;

use KonradMichalik\Typo3LetterAvatar\Configuration;

class ColorUtility
{
    public static function getPairColors(): array
    {
        return $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][Configuration::EXT_KEY]['configuration']['pairs'][array_rand($GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][Configuration::EXT_KEY]['configuration']['pairs'])];
    }

    public static function getRandomColors(): array
    {
        $foregroundColors = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][Configuration::EXT_KEY]['configuration']['random']['foregrounds'];
        $backgroundColors = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][Configuration::EXT_KEY]['configuration']['random']['backgrounds'];

        return [
            'foreground' => $foregroundColors[array_rand($foregroundColors)],
            'background' => $backgroundColors[array_rand($backgroundColors)],
        ];
    }

    public static function getRandomThemeColors(): array
    {
        $themeColors = self::getThemeColors();
        $foregrounds = $themeColors['foregrounds'];
        $backgrounds = $themeColors['backgrounds'];

        if (empty($foregrounds) || empty($backgrounds)) {
            return [];
        }

        return [
            'foreground' => $foregrounds[array_rand($foregrounds)],
            'background' => $backgrounds[array_rand($backgrounds)],
        ];
    }

    public static function getThemeColors(): array
    {
        $themeConfiguration = ConfigurationUtility::get('theme');

        $themeConfiguration = is_array($themeConfiguration) ? $themeConfiguration : [$themeConfiguration];
        $foregroundColors = [];

        foreach ($themeConfiguration as $theme) {
            $themeConfig = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][Configuration::EXT_KEY]['configuration']['themes'][$theme];
            if (isset($themeConfig['foregrounds']) && is_array($themeConfig['foregrounds'])) {
                $foregroundColors = array_merge($foregroundColors, $themeConfig['foregrounds']);
            }
        }

        $backgroundColors = [];

        foreach ($themeConfiguration as $theme) {
            $themeConfig = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][Configuration::EXT_KEY]['configuration']['themes'][$theme];
            if (isset($themeConfig['backgrounds']) && is_array($themeConfig['backgrounds'])) {
                $backgroundColors = array_merge($backgroundColors, $themeConfig['backgrounds']);
            }
        }
        return [
            'foregrounds' => $foregroundColors,
            'backgrounds' => $backgroundColors,
        ];
    }
}
