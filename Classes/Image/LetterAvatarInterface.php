<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Image;
interface LetterAvatarInterface
{
    function generate();

    function saveAs($path): bool;
}
