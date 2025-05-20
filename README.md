<div align="center">

![Extension icon](Resources/Public/Icons/Extension.svg)

# TYPO3 extension `typo3_letter_avatar`

[![License](https://poser.pugx.org/konradmichalik/typo3-letter-avatar/license)](LICENSE.md)

</div>

> [!NOTE]
> Under active development

This extension generates colorful backend user avatars using name initials letter.

![user-list.jpg](Documentation/Images/user-list.jpg)

## Features

* Works out of the box
* Highly customizable
* Supports color themes

## Requirements

* TYPO3 >= 12.4 & PHP 8.1+

## Installation

### Composer

[![Packagist](https://img.shields.io/packagist/v/konradmichalik/typo3-letter-avatar?label=version&logo=packagist)](https://packagist.org/packages/xima/xima-typo3-content-planner)
[![Packagist Downloads](https://img.shields.io/packagist/dt/konradmichalik/typo3-letter-avatar?color=brightgreen)](https://packagist.org/packages/xima/xima-typo3-content-planner)

``` bash
composer require konradmichalik/typo3-letter-avatar
```

### TER

[![TER version](https://typo3-badges.dev/badge/typo3_letter_avatar/version/shields.svg)](https://extensions.typo3.org/extension/xima_typo3_content_planner)
[![TER downloads](https://typo3-badges.dev/badge/typo3_letter_avatar/downloads/shields.svg)](https://extensions.typo3.org/extension/xima_typo3_content_planner)

Download the zip file from [TYPO3 extension repository (TER)](https://extensions.typo3.org/extension/typo3_letter_avatar).

## Setup

Set up the extension after the installation:

``` bash
vendor/bin/typo3 extension:setup --extension=typo3_letter_avatar
```

## Usage

The extension works for backend users out of the box. 

If you want to use it for other use cases, you can use the following code snippet:

```php
\KonradMichalik\Typo3LetterAvatar\Image\Avatar::create(
    name: 'Konrad Michalik',
    mode: KonradMichalik\Typo3LetterAvatar\Enum\ColorMode::RANDOM
)->saveAs('path/to/file.png');
```

## Command

Clear all generated avatar images with the following command:

```bash
vendor/bin/typo3 avatar:clear
```

## EventListener

You can use the `BackendUserAvatarConfigurationEvent` to modify the avatar configuration according to the current backend user:

```php
<?php

declare(strict_types=1);

namespace Vendor\Package\EventListener;

use KonradMichalik\Typo3LetterAvatar\Enum\ColorMode;
use KonradMichalik\Typo3LetterAvatar\Event\BackendUserAvatarConfigurationEvent;

class ModifyLetterAvatarEventListener
{
    public function __invoke(BackendUserAvatarConfigurationEvent $event): void
    {
        $backendUser = $event->getBackendUser();
        
        /*
         * Example: If the backend user is an admin, set the CUSTOM color mode and define custom colors.
         */ 
        if ($backendUser['admin'] === 1) {
            $configuration = $event->getConfiguration();
            $configuration['mode'] = ColorMode::CUSTOM;
            $configuration['foreground'] = '#000000';
            $configuration['background'] = '#FFFFFF';
            
            $event->setConfiguration($configuration);
        }
    }
}
```

> [!NOTE]
> Don't forget to [register the event listener](https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/ApiOverview/Events/EventDispatcher/Index.html#registering-the-event-listener-via-file-services-yaml).


## Development

Use the following ddev command to easily install all supported TYPO3 versions for locale development.

```bash
ddev install all
```

## Credits

This project is highly inspired by [avatar](https://github.com/laravolt/avatar) and [letter-avatar](https://github.com/yohangdev/letter-avatar).

## License

This project is licensed
under [GNU General Public License 2.0 (or later)](LICENSE.md).
