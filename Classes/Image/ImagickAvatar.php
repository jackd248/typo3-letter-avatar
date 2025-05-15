<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Image;

use InvalidArgumentException;
use KonradMichalik\Typo3LetterAvatar\Configuration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ImagickAvatar extends AbstractImageProvider implements LetterAvatarInterface
{
    public function generate(): \Imagick
    {
        $this->nameInitials = $this->getInitials($this->name);
        $backgroundColor = $this->resolveBackgroundColor();
        $foregroundColor = $this->resolveForegroundColor();

        $canvas = new \Imagick();
        $canvas->newImage(480, 480, new \ImagickPixel('transparent'));
        $canvas->setImageFormat('png');

        $circle = new \ImagickDraw();
        $circle->setFillColor(new \ImagickPixel($backgroundColor));
        $circle->circle(240, 240, 240, 0);
        $canvas->drawImage($circle);

        $text = new \ImagickDraw();
        $text->setFont(GeneralUtility::getFileAbsFileName('EXT:' . Configuration::EXT_KEY . '/Resources/Public/Fonts/arial-bold.ttf'));
        $text->setFontSize(220);
        $text->setFillColor(new \ImagickPixel($foregroundColor));
        $text->setTextAlignment(\Imagick::ALIGN_CENTER);
        $text->annotation(240, 320, $this->nameInitials);

        $canvas->drawImage($text);
        $canvas->resizeImage($this->size, $this->size, \Imagick::FILTER_LANCZOS, 1);

        return $canvas;
    }

    public function saveAs($path, $mimetype = self::MIME_TYPE_PNG, $quality = 90): bool
    {
        if (empty($path)) {
            return false;
        }

        if (!in_array($mimetype, self::MIME_TYPES, true)) {
            throw new InvalidArgumentException("Invalid mimetype: $mimetype", 3352303696);
        }

        $image = $this->generate();
        $image->setImageFormat($mimetype === self::MIME_TYPE_JPEG ? 'jpeg' : 'png');

        if ($mimetype === self::MIME_TYPE_JPEG) {
            $image->setImageCompressionQuality($quality);
        }

        return $image->writeImage($path);
    }
}
