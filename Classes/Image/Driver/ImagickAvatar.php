<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Image\Driver;

use KonradMichalik\Typo3LetterAvatar\Enum\ImageFormat;
use KonradMichalik\Typo3LetterAvatar\Image\AbstractImageProvider;
use KonradMichalik\Typo3LetterAvatar\Image\LetterAvatarInterface;
use KonradMichalik\Typo3LetterAvatar\Utility\StringUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ImagickAvatar extends AbstractImageProvider implements LetterAvatarInterface
{
    public function generate(): \Imagick
    {
        $nameInitials = StringUtility::resolveInitials($this->name, $this->initials, $this->transform);
        $backgroundColor = $this->colorizeService->resolveBackgroundColor();
        $foregroundColor = $this->colorizeService->resolveForegroundColor();

        $canvas = new \Imagick();

        $canvas->newImage($this->size, $this->size, new \ImagickPixel('transparent'));
        $canvas->setImageFormat($this->imageFormat->value);

        $circle = new \ImagickDraw();
        $circle->setFillColor(new \ImagickPixel($backgroundColor));
        $circle->circle($this->size/2, $this->size/2, $this->size/2, 0);
        $canvas->drawImage($circle);

        $text = new \ImagickDraw();
        $text->setFont(GeneralUtility::getFileAbsFileName($this->fontPath));
        $text->setFontSize($this->size * $this->fontSize);
        $text->setFillColor(new \ImagickPixel($foregroundColor));
        $text->setTextAlignment(\Imagick::ALIGN_CENTER);
        $text->annotation($this->size/2, $this->size/1.5, $nameInitials);

        $canvas->drawImage($text);

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
}
