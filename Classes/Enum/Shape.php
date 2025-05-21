<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Enum;

enum Shape: string implements EnumInterface
{
    case CIRCLE = 'circle';
    case SQUARE = 'square';
}
