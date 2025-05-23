<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Image\Driver;

use KonradMichalik\Typo3LetterAvatar\Enum\ImageFormat;
use KonradMichalik\Typo3LetterAvatar\Enum\Shape;
use KonradMichalik\Typo3LetterAvatar\Image\AbstractImageProvider;
use KonradMichalik\Typo3LetterAvatar\Image\LetterAvatarInterface;
use KonradMichalik\Typo3LetterAvatar\Utility\PathUtility;
use KonradMichalik\Typo3LetterAvatar\Utility\StringUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Gd extends AbstractImageProvider implements LetterAvatarInterface
{
    public function generate()
    {
        $initials = StringUtility::resolveInitials($this->name, $this->initials, $this->transform);
        $canvas = $this->createCanvas();

        if ($canvas === false) {
            return false;
        }

        $bgColor = $this->allocateColor($canvas, $this->colorizeService->resolveBackgroundColor());
        $fgColor = $this->allocateColor($canvas, $this->colorizeService->resolveForegroundColor());

        if ($this->shape === Shape::CIRCLE) {
            imagefilledellipse($canvas, $this->size / 2, $this->size / 2, $this->size, $this->size, $bgColor);
        } elseif ($this->shape === Shape::SQUARE) {
            imagefilledrectangle($canvas, 0, 0, $this->size, $this->size, $bgColor);
        }

        $this->drawText($canvas, $initials, $fgColor);

        return $canvas;
    }

    public function save(?string $path = null, ImageFormat $format = ImageFormat::PNG, int $quality = 90): string
    {
        if (is_null($path)) {
            $filename = $this->configToHash() . '.' . $format->value;
            $path = PathUtility::getImageFolder() . $filename;
        }

        $image = $this->generate();
        match ($format) {
            ImageFormat::JPEG => imagejpeg($image, $path, $quality),
            ImageFormat::PNG => imagepng($image, $path),
        };

        imagedestroy($image);
        return $path;
    }

    private function createCanvas(): \GdImage|false
    {
        $canvas = imagecreatetruecolor($this->size, $this->size);
        imagesavealpha($canvas, true);
        $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
        imagefill($canvas, 0, 0, $transparent);
        return $canvas;
    }

    private function allocateColor(\GdImage $canvas, string $hexColor): int|bool
    {
        [$r, $g, $b] = sscanf($hexColor, '#%02x%02x%02x');
        return imagecolorallocate($canvas, $r, $g, $b);
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
