<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Image;


use KonradMichalik\Typo3LetterAvatar\Enum\ColorMode;
use KonradMichalik\Typo3LetterAvatar\Utility\ColorUtility;

abstract class AbstractImageProvider
{
    public const MIME_TYPE_PNG = 'png';
    public const MIME_TYPE_JPEG = 'jpeg';
    public const MIME_TYPES = [
        self::MIME_TYPE_PNG,
        self::MIME_TYPE_JPEG,
    ];

    public function __construct(
        protected string $name,
        protected int $size = 48,
        protected string $foregroundColor = '',
        protected string $backgroundColor = '',
        protected ColorMode $mode = ColorMode::CUSTOM,
        protected string $theme = '',
    )
    {
    }

    public function configToHash(): string {
        $parts = [
            $this->name,
            $this->size,
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
        }
    }

    protected function getInitials(string $name): string
    {
        $nameParts = $this->breakName($name);

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
        return array_values(array_filter(explode(' ', $name), fn($word) => $word !== '' && $word !== ','));
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
