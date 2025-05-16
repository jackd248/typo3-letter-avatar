<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Utility;

use KonradMichalik\Typo3LetterAvatar\Configuration;
use KonradMichalik\Typo3LetterAvatar\Enum\EnumInterface;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class ConfigurationUtility
{
    public static function get(string $key, ?string $expectedEnumClass = null): array|string|int|float|bool|EnumInterface|null
    {
        $configuration = array_merge(
            GeneralUtility::makeInstance(ExtensionConfiguration::class)->get(Configuration::EXT_KEY)['general'],
            $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][Configuration::EXT_KEY]['configuration'] ?? [],
        );

        $value = $configuration[$key] ?? null;
        if ($value === null) {
            return null;
        }

        if ($expectedEnumClass !== null) {
            $enumClass = new \ReflectionClass($expectedEnumClass);
            if ($enumClass->isSubclassOf(EnumInterface::class) &&
                $enumClass->isEnum() &&
                !($value instanceof $expectedEnumClass)
            ) {
                if (method_exists($expectedEnumClass, 'tryFrom')) {
                    return $expectedEnumClass::tryFrom($value);
                }
            }
        }

        return $value;
    }
}
