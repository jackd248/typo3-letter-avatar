<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Image;

use KonradMichalik\Typo3LetterAvatar\Enum\ImageFormat;

interface LetterAvatarInterface
{
    public function generate();

    public function save(?string $path = null, ImageFormat $format = ImageFormat::PNG, int $quality = 90): string;

    public function getImagePath(?string $filename = null): string;
    public function getWebPath(?string $filename = null): string;
}
