<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\AvatarProvider;

use KonradMichalik\Typo3LetterAvatar\Configuration;
use KonradMichalik\Typo3LetterAvatar\Service\AbstractImageProvider;
use KonradMichalik\Typo3LetterAvatar\Utility\ColorUtility;
use KonradMichalik\Typo3LetterAvatar\Utility\ImageDriverUtility;
use TYPO3\CMS\Backend\Backend\Avatar\AvatarProviderInterface;
use TYPO3\CMS\Backend\Backend\Avatar\Image;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

class LetterAvatarProvider implements AvatarProviderInterface
{
    public function getImage(array $backendUser, $size): ?Image
    {
        $colors = ColorUtility::getColors();
        $avatarService = ImageDriverUtility::resolveAvatarService(
            name: $this->getName($backendUser),
            foregroundColor: $colors['foreground'] ?? '',
            backgroundColor: $colors['background'] ?? '',
        );
        $fileName = $avatarService->configToHash() . '.png';
        $filePath = $this->getImageFolder() . $fileName;

        if (!file_exists($filePath)) {
            $avatarService->saveAs($filePath, AbstractImageProvider::MIME_TYPE_PNG);
        }

        return GeneralUtility::makeInstance(
            Image::class,
            $this->getWebPath($fileName),
            $this->getConfiguration('width'),
            $this->getConfiguration('height'),
        );
    }

    private function getImageFolder(): string
    {
        $folder = Environment::getPublicPath() . '/typo3temp/assets/avatars/';
        if (!is_dir($folder)) {
            GeneralUtility::mkdir_deep($folder);
        }
        return $folder;
    }

    private function getWebPath(string $filename): string
    {
        return PathUtility::getAbsoluteWebPath('/typo3temp/assets/avatars/' . $filename);
    }

    private function getName(array $backendUser): string
    {
        return $this->getConfiguration('prioritizeRealName') ?
            ($backendUser['realName'] ?: $backendUser['username']) :
            $backendUser['username'];
    }

    private function getConfiguration(string $key): string|int
    {
        $configuration = array_merge(
            GeneralUtility::makeInstance(ExtensionConfiguration::class)->get(Configuration::EXT_KEY)['general'],
            $GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][Configuration::EXT_KEY]['configuration'] ?? [],
        );

        return $configuration[$key] ?? '';
    }

    private function getRandomElement(array $array, mixed $default): mixed
    {
        if (empty($array)) {
            return $default;
        }

        $randomKey = array_rand($array);
        return $array[$randomKey];
    }
}
