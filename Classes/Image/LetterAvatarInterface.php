<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Image;

use KonradMichalik\Typo3LetterAvatar\Enum\ImageFormat;

interface LetterAvatarInterface
{
    public function generate();

    public function saveAs(string $path, ImageFormat $format = ImageFormat::PNG, int $quality = 90): bool;

    public function configToHash(): string;
}
