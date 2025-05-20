<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Image\Driver;

use KonradMichalik\Typo3LetterAvatar\Enum\ImageFormat;
use KonradMichalik\Typo3LetterAvatar\Image\AbstractImageProvider;
use KonradMichalik\Typo3LetterAvatar\Image\LetterAvatarInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Gd extends AbstractImageProvider implements LetterAvatarInterface
{
    public function generate()
    {
        $initials = $this->resolveInitials();
        $bgColor = $this->allocateColor($this->colorizeService->resolveBackgroundColor());
        $fgColor = $this->allocateColor($this->colorizeService->resolveForegroundColor());

        $canvas = $this->createCanvas();

        if ($canvas === false) {
            return false;
        }
        imagefilledellipse($canvas, $this->size / 2, $this->size / 2, $this->size, $this->size, $bgColor);

        $this->drawText($canvas, $initials, $fgColor);

        return $canvas;
    }

    public function saveAs(string $path, ImageFormat $format = ImageFormat::PNG, int $quality = 90): bool
    {
        if (!$path) {
            return false;
        }

        $image = $this->generate();

        $saved = match ($format) {
            ImageFormat::JPEG => imagejpeg($image, $path, $quality),
            ImageFormat::PNG => imagepng($image, $path),
        };

        imagedestroy($image);
        return $saved;
    }

    private function createCanvas(): \GdImage|false
    {
        $canvas = imagecreatetruecolor($this->size, $this->size);
        imagesavealpha($canvas, true);
        $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
        imagefill($canvas, 0, 0, $transparent);
        return $canvas;
    }

    private function allocateColor(string $hexColor): int|bool
    {
        [$r, $g, $b] = sscanf($hexColor, '#%02x%02x%02x');
        return imagecolorallocate($this->createCanvas(), $r, $g, $b);
    }

    private function drawText($canvas, string $text, $color): void
    {
        $fontPath = GeneralUtility::getFileAbsFileName($this->fontPath);
        $fontSize = $this->size * $this->fontSize;
        $textBox = imagettfbbox($fontSize, 0, $fontPath, $text);
        $x = ($this->size - ($textBox[2] - $textBox[0])) / 2;
        $y = ($this->size - ($textBox[5] - $textBox[1])) / 2 + $fontSize / 2;
        imagettftext($canvas, $fontSize, 0, (int)$x, (int)$y, $color, $fontPath, $text);
    }
}
