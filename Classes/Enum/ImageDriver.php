<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Enum;

enum ImageDriver: string
{
    case IMAGICK = 'imagick';
    case GMAGICK = 'gmagick';
}
