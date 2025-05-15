<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Image;

interface LetterAvatarInterface
{
    public function generate();

    public function saveAs($path): bool;
}
