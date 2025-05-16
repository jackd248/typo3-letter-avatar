<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Enum;

enum ImageFormat: string implements EnumInterface
{
    case PNG = 'png';
    case JPEG = 'jpeg';
}
