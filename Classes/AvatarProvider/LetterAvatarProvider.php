<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\AvatarProvider;

use KonradMichalik\Typo3LetterAvatar\Enum\ColorMode;
use KonradMichalik\Typo3LetterAvatar\Enum\ImageFormat;
use KonradMichalik\Typo3LetterAvatar\Enum\Transform;
use KonradMichalik\Typo3LetterAvatar\Event\BackendUserAvatarConfigurationEvent;
use KonradMichalik\Typo3LetterAvatar\Image\Avatar;
use KonradMichalik\Typo3LetterAvatar\Utility\ConfigurationUtility;
use TYPO3\CMS\Backend\Backend\Avatar\AvatarProviderInterface;
use TYPO3\CMS\Backend\Backend\Avatar\Image;
use TYPO3\CMS\Core\EventDispatcher\EventDispatcher;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class LetterAvatarProvider implements AvatarProviderInterface
{
    public function __construct(protected readonly EventDispatcher $eventDispatcher)
    {
    }

    public function getImage(array $backendUser, $size): ?Image
    {
        $mode = ConfigurationUtility::get('colorMode', ColorMode::class);
        if ($mode === null) {
            throw new \InvalidArgumentException('Invalid color mode', 1204028706);
        }

        $configuration = [
            'name' => $this->getName($backendUser),
            'mode' => $mode,
            'theme' => ($mode === ColorMode::THEME) ? ConfigurationUtility::get('theme') : '',
            'size' => ConfigurationUtility::get('size'),
            'fontSize' => ConfigurationUtility::get('fontSize'),
            'fontPath' => ConfigurationUtility::get('fontPath'),
            'imageFormat' => ConfigurationUtility::get('imageFormat', ImageFormat::class),
            'transform' => ConfigurationUtility::get('transform', Transform::class),
        ];

        $this->eventDispatcher->dispatch(new BackendUserAvatarConfigurationEvent($backendUser, $configuration));
        $avatarService = Avatar::create(...$configuration);

        if (!file_exists($avatarService->getImagePath())) {
            $avatarService->save();
        }

        return GeneralUtility::makeInstance(
            Image::class,
            $avatarService->getWebPath(),
            ConfigurationUtility::get('size'),
            ConfigurationUtility::get('size'),
        );
    }
    private function getName(array $backendUser): string
    {
        if (ConfigurationUtility::get('prioritizeRealName')) {
            return $backendUser['realName'] ?: $backendUser['username'];
        }

        return $backendUser['username'];
    }
}
