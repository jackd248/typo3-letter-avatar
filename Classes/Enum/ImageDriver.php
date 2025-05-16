<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Enum;

enum ImageDriver: string implements EnumInterface
{
    case IMAGICK = 'ImageMagick';
    case GMAGICK = 'GraphicsMagick';

    case GD = 'gd';
}
