<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Image;

use KonradMichalik\Typo3LetterAvatar\Enum\ImageDriver;
use KonradMichalik\Typo3LetterAvatar\Image\Driver\GdAvatar;
use KonradMichalik\Typo3LetterAvatar\Image\Driver\GmagickAvatar;
use KonradMichalik\Typo3LetterAvatar\Image\Driver\ImagickAvatar;

class Avatar
{
    public static function create(...$args): LetterAvatarInterface
    {
        $imageDriver = $args['imageDriver'] ?? $GLOBALS['TYPO3_CONF_VARS']['GFX']['processor'];

        switch ($imageDriver) {
            case ImageDriver::IMAGICK:
            default:
                return new ImagickAvatar(...$args);
            case ImageDriver::GD:
                return new GdAvatar(...$args);
            case ImageDriver::GMAGICK:
                return new GmagickAvatar(...$args);
        }
    }
}
