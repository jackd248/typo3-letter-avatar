<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\AvatarProvider;

use KonradMichalik\Typo3LetterAvatar\Enum\ColorMode;
use KonradMichalik\Typo3LetterAvatar\Enum\ImageFormat;
use KonradMichalik\Typo3LetterAvatar\Enum\Transform;
use KonradMichalik\Typo3LetterAvatar\Utility\ConfigurationUtility;
use KonradMichalik\Typo3LetterAvatar\Utility\ImageDriverUtility;
use KonradMichalik\Typo3LetterAvatar\Utility\PathUtility;
use TYPO3\CMS\Backend\Backend\Avatar\AvatarProviderInterface;
use TYPO3\CMS\Backend\Backend\Avatar\Image;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class LetterAvatarProvider implements AvatarProviderInterface
{
    public function getImage(array $backendUser, $size): ?Image
    {
        $mode = ConfigurationUtility::get('colorMode', ColorMode::class);
        if ($mode === null) {
            throw new \InvalidArgumentException('Invalid color mode', 1204028706);
        }

        $imageFormat = ConfigurationUtility::get('imageFormat', ImageFormat::class);
        $avatarService = ImageDriverUtility::resolveAvatarService(
            name: $this->getName($backendUser),
            mode: $mode,
            theme: ($mode === ColorMode::THEME) ? ConfigurationUtility::get('theme') : '',
            size: ConfigurationUtility::get('size'),
            fontSize: ConfigurationUtility::get('fontSize'),
            fontPath: ConfigurationUtility::get('fontPath'),
            imageFormat: ConfigurationUtility::get('imageFormat', ImageFormat::class),
            transform: ConfigurationUtility::get('transform', Transform::class),
        );

        $fileName = $avatarService->configToHash() . '.' . $imageFormat->value;
        $filePath = PathUtility::getImageFolder() . $fileName;

        if (!file_exists($filePath)) {
            $avatarService->saveAs($filePath);
        }

        return GeneralUtility::makeInstance(
            Image::class,
            PathUtility::getWebPath($fileName),
            ConfigurationUtility::get('size'),
            ConfigurationUtility::get('size'),
        );
    }
    private function getName(array $backendUser): string
    {
        return ConfigurationUtility::get('prioritizeRealName') ?
            ($backendUser['realName'] ?: $backendUser['username']) :
            $backendUser['username'];
    }
}
