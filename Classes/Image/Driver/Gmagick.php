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

class Gmagick extends AbstractImageProvider implements LetterAvatarInterface
{
    public function generate(): \Gmagick
    {
        $canvas = $this->createCanvas();
        $backgroundColor = $this->colorizeService->resolveBackgroundColor();

        if ($this->shape === Shape::CIRCLE) {
            $this->drawEllipse($canvas, $backgroundColor);
        } elseif ($this->shape === Shape::SQUARE) {
            $this->drawSquare($canvas, $backgroundColor);
        }

        $this->drawText(
            $canvas,
            StringUtility::resolveInitials($this->name, $this->initials, $this->transform),
            $this->colorizeService->resolveForegroundColor()
        );
        return $canvas;
    }

    public function save(?string $path = null, ImageFormat $format = ImageFormat::PNG, int $quality = 90): string
    {
        if (is_null($path)) {
            $filename = $this->configToHash() . '.' . $format->value;
            $path = PathUtility::getImageFolder() . $filename;
        }

        $image = $this->generate();
        $image->setimageformat($format->value);

        if ($format === ImageFormat::JPEG) {
            $image->setcompressionquality($quality);
        }

        $image->write($path);
        return $path;
    }

    private function createCanvas(): \Gmagick
    {
        $canvas = new \Gmagick();
        $canvas->newimage($this->size, $this->size, 'transparent');
        $canvas->setimageformat($this->imageFormat->value);
        return $canvas;
    }

    private function drawEllipse(\Gmagick $canvas, string $color): void
    {
        $draw = new \GmagickDraw();
        $draw->setfillcolor($color);
        $draw->ellipse($this->size / 2, $this->size / 2, $this->size / 2, $this->size / 2, 0, 360);
        $canvas->drawimage($draw);
    }

    private function drawSquare(\Gmagick $canvas, string $color): void
    {
        $draw = new \GmagickDraw();
        $draw->setfillcolor($color);
        $draw->rectangle(0, 0, $this->size, $this->size);
        $canvas->drawimage($draw);
    }

    private function drawText(\Gmagick $canvas, string $text, string $color): void
    {
        $draw = new \GmagickDraw();
        $draw->setfillcolor($color);
        $draw->setfont(GeneralUtility::getFileAbsFileName($this->fontPath));
        $draw->setfontsize($this->size * $this->fontSize);

        $metrics = $canvas->queryfontmetrics($draw, $text);
        $textX = ($this->size - $metrics['textWidth']) / 2;
        $textY = ($this->size + $metrics['textHeight']) / 2;

        $draw->annotate($textX, $textY, $text);
        $canvas->drawimage($draw);
    }
}
