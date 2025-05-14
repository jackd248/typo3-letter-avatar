<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\AvatarProvider;

use KonradMichalik\Typo3LetterAvatar\Service\LetterAvatar;
use TYPO3\CMS\Backend\Backend\Avatar\AvatarProviderInterface;
use TYPO3\CMS\Backend\Backend\Avatar\Image;
use TYPO3\CMS\Core\Core\Environment;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

class AvatarProvider implements AvatarProviderInterface
{
    /**
     * Get Image
     *
     * @param array $backendUser be_user record
     * @param int $size
     * @return Image|null
     */
    public function getImage(array $backendUser, $size): ?Image
    {
        $folder = Environment::getPublicPath() . '/typo3temp/assets/avatars/';
        $path = $folder . $backendUser['uid'] . '.png';
        if (!file_exists($folder)) {
            GeneralUtility::mkdir_deep($folder);
        }
        (new LetterAvatar($backendUser['username']))->saveAs($path, LetterAvatar::MIME_TYPE_PNG, 100);
        $image = GeneralUtility::makeInstance(
            Image::class,
            PathUtility::getAbsoluteWebPath('/typo3temp/assets/avatars/' . $backendUser['uid'] . '.png'),
            200,
            200
        );

        return $image;
    }
}
