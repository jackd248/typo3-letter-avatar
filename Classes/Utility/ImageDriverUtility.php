<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Utility;

use KonradMichalik\Typo3LetterAvatar\Configuration;
use KonradMichalik\Typo3LetterAvatar\Enum\ImageDriver;
use KonradMichalik\Typo3LetterAvatar\Service\ImagickAvatar;
use KonradMichalik\Typo3LetterAvatar\Service\LetterAvatarInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ImageDriverUtility
{
    public static function getImageDriverConfiguration(): ImageDriver
    {
        return ImageDriver::tryFrom(GeneralUtility::makeInstance(ExtensionConfiguration::class)->get(Configuration::EXT_KEY)['general']['imageDriver']) ?? ImageDriver::IMAGICK;
    }

    public static function resolveAvatarService(...$args): LetterAvatarInterface
    {
        switch (self::getImageDriverConfiguration()) {
            case ImageDriver::IMAGICK:
            default:
                return new ImagickAvatar(...$args);
        }
    }
}
