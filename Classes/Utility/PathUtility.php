<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Utility;

use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility as CorePathUtility;

class PathUtility
{
    public static function getImageFolder(): string
    {
        $folder = Environment::getPublicPath() . ConfigurationUtility::get('imagePath');

        if (!str_ends_with($folder, '/')) {
            $folder .= '/';
        }

        if (!is_dir($folder)) {
            GeneralUtility::mkdir_deep($folder);
        }
        return $folder;
    }

    public static function getWebPath(string $filename): string
    {
        return CorePathUtility::getAbsoluteWebPath(ConfigurationUtility::get('imagePath') . $filename);
    }
}
