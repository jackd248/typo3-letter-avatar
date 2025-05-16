<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Utility;

use KonradMichalik\Typo3LetterAvatar\Enum\ImageDriver;
use KonradMichalik\Typo3LetterAvatar\Image\Driver\ImagickAvatar;
use KonradMichalik\Typo3LetterAvatar\Image\LetterAvatarInterface;

class ImageDriverUtility
{
    public static function resolveAvatarService(...$args): LetterAvatarInterface
    {
        switch (ConfigurationUtility::get('imageDriver', ImageDriver::class)) {
            case ImageDriver::IMAGICK:
            default:
                return new ImagickAvatar(...$args);
        }
    }
}
