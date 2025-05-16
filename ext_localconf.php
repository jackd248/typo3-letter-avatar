<?php

declare(strict_types=1);

use KonradMichalik\Typo3LetterAvatar\Configuration;

defined('TYPO3') || die();

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['backend']['avatarProviders']['letterAvatar'] = [
    'provider' => \KonradMichalik\Typo3LetterAvatar\AvatarProvider\LetterAvatarProvider::class,
    'after' => ['defaultAvatarProvider'],
];

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][Configuration::EXT_KEY]['configuration'] = [
    // image size, in pixel
    'size' => 50,

    // font size, in percentage
    'fontSize' => 0.5,

    // font path
    'fontPath' => 'EXT:typo3_letter_avatar/Resources/Public/Fonts/arial-bold.ttf',

    // convert initial letter in uppercase, lowercase or keep as is
    'transform' => \KonradMichalik\Typo3LetterAvatar\Enum\Transform::NONE,

    // prioritize real name or username for initial letters
    'prioritizeRealName' => true,

    // default image path
    'imagePath' => '/typo3temp/assets/avatars/',

    // image format, png or jpeg
    'imageFormat' => \KonradMichalik\Typo3LetterAvatar\Enum\ImageFormat::PNG,

    // color mode
    // 'colorMode' => \KonradMichalik\Typo3LetterAvatar\Enum\ColorMode::STRINGIFY->value,

    'random' => [
        // List of foreground colors to be used, randomly selected based on name supplied
        'foregrounds' => [
            '#FFFFFF',
        ],

        // List of background colors to be used, randomly selected based on name supplied
        'backgrounds' => [
            '#f44336',
            '#E91E63',
            '#9C27B0',
            '#673AB7',
            '#3F51B5',
            '#2196F3',
            '#03A9F4',
            '#00BCD4',
            '#009688',
            '#4CAF50',
            '#8BC34A',
            '#CDDC39',
            '#FFC107',
            '#FF9800',
            '#FF5722',
        ],
    ],

    // Color pair combinations
    'pairs' => [
        [
            'background' => '#626F47',
            'foreground' => '#F0BB78',
        ],
        [
            'background' => '#FE5D26',
            'foreground' => '#C1DBB3',
        ],
        [
            'background' => '#533B4D',
            'foreground' => '#FAE3C6',
        ],
        [
            'background' => '#5409DA',
            'foreground' => '#8DD8FF',
        ],
        [
            'background' => '#096B68',
            'foreground' => '#FFFBDE',
        ],
        [
            'background' => '#2A4759',
            'foreground' => '#F79B72',
        ],
        [
            'background' => '#213448',
            'foreground' => '#ECEFCA',
        ],
    ],

    // Predefined themes
    'themes' => [
        'grayscale-light' => [
            'backgrounds' => ['#edf2f7', '#e2e8f0', '#cbd5e0'],
            'foregrounds' => ['#a0aec0'],
        ],
        'grayscale-dark' => [
            'backgrounds' => ['#2d3748', '#4a5568', '#718096'],
            'foregrounds' => ['#e2e8f0'],
        ],
        'colorful' => [
            'backgrounds' => [
                '#f44336',
                '#E91E63',
                '#9C27B0',
                '#673AB7',
                '#3F51B5',
                '#2196F3',
                '#03A9F4',
                '#00BCD4',
                '#009688',
                '#4CAF50',
                '#8BC34A',
                '#CDDC39',
                '#FFC107',
                '#FF9800',
                '#FF5722',
            ],
            'foregrounds' => [
                '#FFFFFF',
            ],
        ],
        'pastel' => [
            'backgrounds' => [
                '#ef9a9a',
                '#F48FB1',
                '#CE93D8',
                '#B39DDB',
                '#9FA8DA',
                '#90CAF9',
                '#81D4FA',
                '#80DEEA',
                '#80CBC4',
                '#A5D6A7',
                '#E6EE9C',
                '#FFAB91',
                '#FFCCBC',
                '#D7CCC8',
            ],
            'foregrounds' => [
                '#FFFFFF',
            ],
        ],
    ],
];
