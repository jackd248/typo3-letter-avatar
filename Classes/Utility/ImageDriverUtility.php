<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Utility;

use KonradMichalik\Typo3LetterAvatar\Enum\ImageDriver;
use KonradMichalik\Typo3LetterAvatar\Image\Driver\GdAvatar;
use KonradMichalik\Typo3LetterAvatar\Image\Driver\ImagickAvatar;
use KonradMichalik\Typo3LetterAvatar\Image\LetterAvatarInterface;
use TYPO3\CMS\Core\Utility\Exception\NotImplementedMethodException;

class ImageDriverUtility
{
    public static function resolveAvatarService(...$args): LetterAvatarInterface
    {
        switch (ConfigurationUtility::get('imageDriver', ImageDriver::class)) {
            case ImageDriver::IMAGICK:
            default:
                return new ImagickAvatar(...$args);
            case ImageDriver::GD:
                return new GdAvatar(...$args);
            case ImageDriver::GMAGICK:
                throw new NotImplementedMethodException();
        }
    }
}
