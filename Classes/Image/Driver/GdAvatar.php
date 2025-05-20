<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Image\Driver;

use KonradMichalik\Typo3LetterAvatar\Enum\ImageFormat;
use KonradMichalik\Typo3LetterAvatar\Image\AbstractImageProvider;
use KonradMichalik\Typo3LetterAvatar\Image\LetterAvatarInterface;
use KonradMichalik\Typo3LetterAvatar\Utility\StringUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class GdAvatar extends AbstractImageProvider implements LetterAvatarInterface
{
    public function generate()
    {
        $nameInitials = StringUtility::resolveInitials($this->name, $this->initials, $this->transform);
        $backgroundColor = $this->colorizeService->resolveBackgroundColor();
        $foregroundColor = $this->colorizeService->resolveForegroundColor();

        $canvas = imagecreatetruecolor($this->size, $this->size);
        imagesavealpha($canvas, true);
        $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
        imagefill($canvas, 0, 0, $transparent);

        [$bgR, $bgG, $bgB] = sscanf($backgroundColor, '#%02x%02x%02x');
        $bgColor = imagecolorallocate($canvas, $bgR, $bgG, $bgB);
        imagefilledellipse($canvas, $this->size / 2, $this->size / 2, $this->size, $this->size, $bgColor);

        [$fgR, $fgG, $fgB] = sscanf($foregroundColor, '#%02x%02x%02x');
        $fgColor = imagecolorallocate($canvas, $fgR, $fgG, $fgB);

        $fontPath = GeneralUtility::getFileAbsFileName($this->fontPath);
        $fontSize = $this->size * $this->fontSize;
        $textBox = imagettfbbox($fontSize, 0, $fontPath, $nameInitials);
        $textX = ($this->size - ($textBox[2] - $textBox[0])) / 2;
        $textY = ($this->size - ($textBox[5] - $textBox[1])) / 2;
        $textY += $fontSize / 2;
        imagettftext($canvas, $fontSize, 0, (int)$textX, (int)$textY, $fgColor, $fontPath, $nameInitials);

        return $canvas;
    }

    public function saveAs(string $path, ImageFormat $format = ImageFormat::PNG, int $quality = 90): bool
    {
        if (empty($path)) {
            return false;
        }

        $image = $this->generate();

        switch ($format) {
            case ImageFormat::JPEG:
                imagejpeg($image, $path, $quality);
                break;
            case ImageFormat::PNG:
                imagepng($image, $path);
                break;
            default:
                return false;
        }

        imagedestroy($image);
        return true;
    }
}
