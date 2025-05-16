<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Utility;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PathUtility
{
    public static function getImageFolder(): string
    {
        $folder = Environment::getPublicPath() . ConfigurationUtility::get('imagePath');
        if (!is_dir($folder)) {
            GeneralUtility::mkdir_deep($folder);
        }
        return $folder;
    }

    public static function getWebPath(string $filename): string
    {
        return \TYPO3\CMS\Core\Utility\PathUtility::getAbsoluteWebPath(ConfigurationUtility::get('imagePath') . $filename);
    }
}
