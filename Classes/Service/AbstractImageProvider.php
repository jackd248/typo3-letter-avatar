<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Service;


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
        protected string $backgroundColor = ''
    )
    {
    }

    public function configToHash(): string {
        $parts = [
            $this->name,
            $this->size,
            $this->foregroundColor,
            $this->backgroundColor,
        ];
        return md5(implode('_', $parts));
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
