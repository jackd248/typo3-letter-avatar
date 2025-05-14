<?php

declare(strict_types=1);

use KonradMichalik\Typo3LetterAvatar\Configuration;

defined('TYPO3') || die();

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['backend']['avatarProviders']['letterAvatar'] = [
    'provider' => \KonradMichalik\Typo3LetterAvatar\AvatarProvider\LetterAvatarProvider::class,
    'after' => ['defaultAvatarProvider']
];

$GLOBALS['TYPO3_CONF_VARS']['EXTCONF'][Configuration::EXT_KEY]['configuration'] = [
    // Image width, in pixel
    'width' => 100,

    // Image height, in pixel
    'height' => 100,

    // font size
    'fontSize' => 48,

    // convert initial letter in uppercase
    'uppercase' => false,

    'colorMode' => \KonradMichalik\Typo3LetterAvatar\Enum\ColorMode::STRINGIFY->value,

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

    // List of theme name to be used when rendering avatar
    // Possible values are:
    // 1. Theme name as string: 'colorful'
    // 2. Or array of string name: ['grayscale-light', 'grayscale-dark']
    // 3. Or wildcard "*" to use all defined themes
    'theme' => ['colorful'],

    // Predefined themes
    // Available theme attributes are:
    // shape, chars, backgrounds, foregrounds, fonts, fontSize, width, height, ascii, uppercase, and border.
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
            'foregrounds' => ['#FFFFFF'],
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
                '#FFF',
            ],
        ],
    ],
];
