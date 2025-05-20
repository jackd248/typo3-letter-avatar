<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Image;

use KonradMichalik\Typo3LetterAvatar\Configuration;
use KonradMichalik\Typo3LetterAvatar\Enum\ColorMode;
use KonradMichalik\Typo3LetterAvatar\Enum\ImageFormat;
use KonradMichalik\Typo3LetterAvatar\Enum\Transform;
use KonradMichalik\Typo3LetterAvatar\Service\Colorize;

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
            $this->transform->value,
        ];
        return md5(implode('_', $parts));
    }

    public function resolveInitials(): string
    {
        if ($this->initials !== '') {
            return $this->transform($this->initials);
        }
        $nameParts = $this->breakName($this->name);

        if (!$nameParts) {
            return '';
        }

        $secondLetter = isset($nameParts[1]) ? $this->getFirstLetter($nameParts[1]) : '';

        return $this->transform($this->getFirstLetter($nameParts[0]) . $secondLetter);
    }

    protected function transform(string $string): string
    {
        return match ($this->transform) {
            Transform::UPPERCASE => mb_strtoupper($string),
            Transform::LOWERCASE => mb_strtolower($string),
            default => $string,
        };
    }

    protected function getFirstLetter(string $word): string
    {
        return mb_strtoupper(trim(mb_substr($word, 0, 1, 'UTF-8')));
    }

    protected function breakName(string $name): array
    {
        return array_values(array_filter(explode(' ', $name), fn ($word) => $word !== '' && $word !== ','));
    }
}
