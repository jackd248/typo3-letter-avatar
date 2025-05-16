<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Image;

use KonradMichalik\Typo3LetterAvatar\Enum\ImageDriver;
use KonradMichalik\Typo3LetterAvatar\Image\Driver\GdAvatar;
use KonradMichalik\Typo3LetterAvatar\Image\Driver\ImagickAvatar;
use KonradMichalik\Typo3LetterAvatar\Utility\ConfigurationUtility;
use TYPO3\CMS\Core\Utility\Exception\NotImplementedMethodException;

class Avatar
{
    public static function create(...$args): LetterAvatarInterface
    {
        $imageDriver = $args['imageDriver'] ?? ConfigurationUtility::get('imageDriver', ImageDriver::class);

        switch ($imageDriver) {
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
