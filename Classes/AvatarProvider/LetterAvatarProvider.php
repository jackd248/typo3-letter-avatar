<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\AvatarProvider;

use KonradMichalik\Typo3LetterAvatar\Enum\ColorMode;
use KonradMichalik\Typo3LetterAvatar\Image\AbstractImageProvider;
use KonradMichalik\Typo3LetterAvatar\Utility\ConfigurationUtility;
use KonradMichalik\Typo3LetterAvatar\Utility\ImageDriverUtility;
use TYPO3\CMS\Backend\Backend\Avatar\AvatarProviderInterface;
use TYPO3\CMS\Backend\Backend\Avatar\Image;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

class LetterAvatarProvider implements AvatarProviderInterface
{
    public function getImage(array $backendUser, $size): ?Image
    {
        $mode = ConfigurationUtility::getConfiguration('colorMode');
        if (!$mode instanceof ColorMode) {
            $mode = ColorMode::tryFrom($mode);
        }
        if ($mode === null) {
            throw new \InvalidArgumentException('Invalid color mode');
        }

        $avatarService = ImageDriverUtility::resolveAvatarService(
            name: $this->getName($backendUser),
            mode: $mode,
            theme: ($mode === ColorMode::THEME) ? ConfigurationUtility::getConfiguration('theme') : '',
        );

        $fileName = $avatarService->configToHash() . '.png';
        $filePath = $this->getImageFolder() . $fileName;

        if (!file_exists($filePath)) {
            $avatarService->saveAs($filePath, AbstractImageProvider::MIME_TYPE_PNG);
        }

        return GeneralUtility::makeInstance(
            Image::class,
            $this->getWebPath($fileName),
            ConfigurationUtility::getConfiguration('width'),
            ConfigurationUtility::getConfiguration('height'),
        );
    }

    private function getImageFolder(): string
    {
        $folder = Environment::getPublicPath() . ConfigurationUtility::getConfiguration('avatarPath');
        if (!is_dir($folder)) {
            GeneralUtility::mkdir_deep($folder);
        }
        return $folder;
    }

    private function getWebPath(string $filename): string
    {
        return PathUtility::getAbsoluteWebPath(ConfigurationUtility::getConfiguration('avatarPath') . $filename);
    }

    private function getName(array $backendUser): string
    {
        return ConfigurationUtility::getConfiguration('prioritizeRealName') ?
            ($backendUser['realName'] ?: $backendUser['username']) :
            $backendUser['username'];
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
