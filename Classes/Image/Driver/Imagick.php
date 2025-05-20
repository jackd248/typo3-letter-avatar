<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Image\Driver;

use KonradMichalik\Typo3LetterAvatar\Enum\ImageFormat;
use KonradMichalik\Typo3LetterAvatar\Image\AbstractImageProvider;
use KonradMichalik\Typo3LetterAvatar\Image\LetterAvatarInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class Imagick extends AbstractImageProvider implements LetterAvatarInterface
{
    public function generate(): \Imagick
    {
        $canvas = $this->createCanvas();
        $this->drawCircle($canvas, $this->colorizeService->resolveBackgroundColor());
        $this->drawText($canvas, $this->resolveInitials(), $this->colorizeService->resolveForegroundColor());
        return $canvas;
    }

    public function saveAs(string $path, ImageFormat $format = ImageFormat::PNG, int $quality = 90): bool
    {
        if (empty($path)) {
            return false;
        }

        $image = $this->generate();
        $image->setImageFormat($format->value);

        if ($format === ImageFormat::JPEG) {
            $image->setImageCompressionQuality($quality);
        }

        return $image->writeImage($path);
    }

    private function createCanvas(): \Imagick
    {
        $canvas = new \Imagick();
        $canvas->newImage($this->size, $this->size, new \ImagickPixel('transparent'));
        $canvas->setImageFormat($this->imageFormat->value);
        return $canvas;
    }

    private function drawCircle(\Imagick $canvas, string $color): void
    {
        $circle = new \ImagickDraw();
        $circle->setFillColor(new \ImagickPixel($color));
        $circle->circle($this->size / 2, $this->size / 2, $this->size / 2, 0);
        $canvas->drawImage($circle);
    }

    private function drawText(\Imagick $canvas, string $text, string $color): void
    {
        $draw = new \ImagickDraw();
        $draw->setFont(GeneralUtility::getFileAbsFileName($this->fontPath));
        $draw->setFontSize($this->size * $this->fontSize);
        $draw->setFillColor(new \ImagickPixel($color));
        $draw->setTextAlignment(\Imagick::ALIGN_CENTER);
        $draw->annotation($this->size / 2, $this->size / 1.5, $text);
        $canvas->drawImage($draw);
    }
}
