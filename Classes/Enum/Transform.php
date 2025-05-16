<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Enum;

enum Transform: string implements EnumInterface
{
    case NONE = 'none';
    case LOWERCASE = 'lowercase';
    case UPPERCASE = 'uppercase';
}
