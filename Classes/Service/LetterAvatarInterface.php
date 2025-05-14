<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Service;
interface LetterAvatarInterface
{
    function generate();

    function saveAs($path): bool;
}
