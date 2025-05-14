<?php

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['backend']['avatarProviders']['letterAvatar'] = [
    'provider' => \KonradMichalik\Typo3LetterAvatar\AvatarProvider\AvatarProvider::class,
    'before' => ['defaultAvatarProvider']
];
