<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Utility;

use KonradMichalik\Typo3LetterAvatar\Enum\Transform;

class StringUtility
{
    public static function resolveInitials(string $name, string $preSetInitials = '', Transform $transform = Transform::NONE): string
    {
        if ($preSetInitials !== '') {
            return self::applyTransform($preSetInitials, $transform);
        }

        $nameParts = self::splitName($name);
        if (empty($nameParts)) {
            return '';
        }

        $initials = self::extractFirstLetter($nameParts[0]);
        if (isset($nameParts[1])) {
            $initials .= self::extractFirstLetter($nameParts[1]);
        }

        return self::applyTransform($initials, $transform);
    }

    protected static function applyTransform(string $string, Transform $transform): string
    {
        return match ($transform) {
            Transform::UPPERCASE => mb_strtoupper($string),
            Transform::LOWERCASE => mb_strtolower($string),
            default => $string,
        };
    }

    protected static function extractFirstLetter(string $word): string
    {
        return mb_strtoupper(mb_substr(trim($word), 0, 1, 'UTF-8'));
    }

    protected static function splitName(string $name): array
    {
        return array_filter(explode(' ', $name), fn ($word) => $word !== '' && $word !== ',');
    }
}
