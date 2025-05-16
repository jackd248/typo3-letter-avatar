<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Image;

use KonradMichalik\Typo3LetterAvatar\Configuration;
use KonradMichalik\Typo3LetterAvatar\Enum\ColorMode;
use KonradMichalik\Typo3LetterAvatar\Enum\ImageFormat;
use KonradMichalik\Typo3LetterAvatar\Utility\ColorUtility;

abstract class AbstractImageProvider
{
    public function __construct(
        protected string $name = '',
        protected string $initials = '',
        protected int $size = 100,
        protected float|int $fontSize = 0.5,
        protected string $fontPath = 'EXT:' . Configuration::EXT_KEY . '/Resources/Public/Fonts/arial-bold.ttf',
        protected string $foregroundColor = '',
        protected string $backgroundColor = '',
        protected ColorMode $mode = ColorMode::CUSTOM,
        protected string $theme = '',
        protected ImageFormat $imageFormat = ImageFormat::PNG,
    ) {
    }

    public function configToHash(): string
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
        ];
        return md5(implode('_', $parts));
    }

    protected function resolveForegroundColor(): string
    {
        switch ($this->mode) {
            case ColorMode::CUSTOM:
            default:
                return $this->foregroundColor;
            case ColorMode::STRINGIFY:
                return ColorUtility::getRandomColors()['foreground'];
            case ColorMode::RANDOM:
                return ColorUtility::getRandomColors()['foreground'];
            case ColorMode::THEME:
                return ColorUtility::getRandomThemeColors()['foreground'];
            case ColorMode::PAIRS:
                return ColorUtility::getPairColors()['foreground'];
        }
    }

    protected function resolveBackgroundColor(): string
    {
        switch ($this->mode) {
            case ColorMode::CUSTOM:
            default:
                return $this->backgroundColor;
            case ColorMode::STRINGIFY:
                return $this->stringToColor($this->name);
            case ColorMode::RANDOM:
                return ColorUtility::getRandomColors()['background'];
            case ColorMode::THEME:
                return ColorUtility::getRandomThemeColors()['background'];
            case ColorMode::PAIRS:
                return ColorUtility::getPairColors()['background'];
        }
    }

    protected function resolveInitials(): string
    {
        if ($this->initials !== '') {
            return $this->initials;
        }
        $nameParts = $this->breakName($this->name);

        if (!$nameParts) {
            return '';
        }

        $secondLetter = isset($nameParts[1]) ? $this->getFirstLetter($nameParts[1]) : '';

        return $this->getFirstLetter($nameParts[0]) . $secondLetter;
    }

    protected function getFirstLetter(string $word): string
    {
        return mb_strtoupper(trim(mb_substr($word, 0, 1, 'UTF-8')));
    }

    protected function breakName(string $name): array
    {
        return array_values(array_filter(explode(' ', $name), fn ($word) => $word !== '' && $word !== ','));
    }

    protected function stringToColor(string $string): string
    {
        $rgb = substr(hash('crc32b', $string), 0, 6);
        $darker = 2;

        $R = sprintf('%02X', hexdec(substr($rgb, 0, 2)) / $darker);
        $G = sprintf('%02X', hexdec(substr($rgb, 2, 2)) / $darker);
        $B = sprintf('%02X', hexdec(substr($rgb, 4, 2)) / $darker);

        return "#$R$G$B";
    }
}
