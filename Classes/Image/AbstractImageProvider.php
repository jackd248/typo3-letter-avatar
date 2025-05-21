<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Image;

use KonradMichalik\Typo3LetterAvatar\Configuration;
use KonradMichalik\Typo3LetterAvatar\Enum\ColorMode;
use KonradMichalik\Typo3LetterAvatar\Enum\ImageFormat;
use KonradMichalik\Typo3LetterAvatar\Enum\Transform;
use KonradMichalik\Typo3LetterAvatar\Service\Colorize;
use KonradMichalik\Typo3LetterAvatar\Utility\PathUtility;

abstract class AbstractImageProvider
{
    protected ?Colorize $colorizeService = null;

    public function __construct(
        public string $name = '',
        public string $initials = '',
        public int $size = 100,
        public float|int $fontSize = 0.5,
        public string $fontPath = 'EXT:' . Configuration::EXT_KEY . '/Resources/Public/Fonts/arial-bold.ttf',
        public string $foregroundColor = '',
        public string $backgroundColor = '',
        public ColorMode $mode = ColorMode::CUSTOM,
        public string $theme = '',
        public ImageFormat $imageFormat = ImageFormat::PNG,
        public Transform $transform = Transform::NONE,
    ) {
        $this->colorizeService = new Colorize($this);
    }

    public function getImagePath(?string $filename = null): string
    {
        return PathUtility::getImageFolder() . ($filename ?: ($this->configToHash() . '.' . $this->imageFormat->value));
    }

    public function getWebPath(?string $filename = null): string
    {
        return PathUtility::getWebPath($filename ?: $this->configToHash() . '.' . $this->imageFormat->value);
    }

    protected function configToHash(): string
    {
        $parts = [
            $this->name,
            $this->initials,
            $this->size,
            $this->fontSize,
            $this->fontPath,
            $this->foregroundColor,
            $this->backgroundColor,
            $this->mode->value,
            $this->theme,
            $this->transform->value,
        ];
        return md5(implode('_', $parts));
    }
}
