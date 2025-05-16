<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Enum;

enum ColorMode: string implements EnumInterface
{
    case CUSTOM = 'custom';
    case STRINGIFY = 'stringify';
    case RANDOM = 'random';
    case THEME = 'theme';
    case PAIRS = 'pairs';
}
