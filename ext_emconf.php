<?php

/** @var string $_EXTKEY */
$EM_CONF[$_EXTKEY] = [
    'title' => 'Letter Avatar',
    'description' => 'This TYPO3 extension generates colorful backend user avatars using name initials letter.',
    'category' => 'module',
    'author' => 'Konrad Michalik',
    'author_email' => 'hej@konradmichalik.dev',
    'state' => 'stable',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'php' => '8.1.0-8.4.99',
            'typo3' => '11.5.0-13.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
