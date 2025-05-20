<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Image\Driver;

use KonradMichalik\Typo3LetterAvatar\Enum\ImageFormat;
use KonradMichalik\Typo3LetterAvatar\Image\AbstractImageProvider;
use KonradMichalik\Typo3LetterAvatar\Image\LetterAvatarInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class GmagickAvatar extends AbstractImageProvider implements LetterAvatarInterface
{
    public function generate(): \Gmagick
    {
        $nameInitials = $this->resolveInitials();
        $backgroundColor = $this->colorizeService->resolveBackgroundColor();
        $foregroundColor = $this->colorizeService->resolveForegroundColor();

        $canvas = new \Gmagick();
        $canvas->newimage($this->size, $this->size, 'transparent');
        $canvas->setimageformat($this->imageFormat->value);

        $draw = new \GmagickDraw();
        $draw->setfillcolor($backgroundColor);
        $draw->ellipse($this->size / 2, $this->size / 2, $this->size / 2, $this->size / 2, 0, 360);
        $canvas->drawimage($draw);

        $draw->setfillcolor($foregroundColor);
        $draw->setfont(GeneralUtility::getFileAbsFileName($this->fontPath));
        $draw->setfontsize($this->size * $this->fontSize);

        $metrics = $canvas->queryfontmetrics($draw, $nameInitials);
        $textX = ($this->size - $metrics['textWidth']) / 2;
        $textY = ($this->size + $metrics['textHeight']) / 2;

        $draw->annotate($textX, $textY, $nameInitials);
        $canvas->drawimage($draw);

        return $canvas;
    }

    public function saveAs(string $path, ImageFormat $format = ImageFormat::PNG, int $quality = 90): bool
    {
        if (empty($path)) {
            return false;
        }

        $image = $this->generate();
        $image->setimageformat($format->value);

        if ($format === ImageFormat::JPEG) {
            $image->setcompressionquality($quality);
        }

        $image->write($path);
        return true;
    }
}
