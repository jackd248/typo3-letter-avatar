<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Image;

use KonradMichalik\Typo3LetterAvatar\Enum\ImageDriver;
use KonradMichalik\Typo3LetterAvatar\Image\Driver\Gd;
use KonradMichalik\Typo3LetterAvatar\Image\Driver\Gmagick;
use KonradMichalik\Typo3LetterAvatar\Image\Driver\Imagick;

class Avatar
{
    public static function create(...$args): LetterAvatarInterface
    {
        $imageDriver = $args['imageDriver'] ?? $GLOBALS['TYPO3_CONF_VARS']['GFX']['processor'];

        switch ($imageDriver) {
            case ImageDriver::IMAGICK:
            default:
                return new Imagick(...$args);
            case ImageDriver::GD:
                return new Gd(...$args);
            case ImageDriver::GMAGICK:
                return new Gmagick(...$args);
        }
    }
}
