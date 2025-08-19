<div align="center">

![Extension icon](Resources/Public/Icons/Extension.svg)

# TYPO3 extension `typo3_letter_avatar`

[![Latest Stable Version](https://typo3-badges.dev/badge/typo3_letter_avatar/version/shields.svg)](https://extensions.typo3.org/extension/typo3_letter_avatar)
[![Supported TYPO3 versions](https://typo3-badges.dev/badge/typo3_letter_avatar/typo3/shields.svg)](https://extensions.typo3.org/extension/typo3_letter_avatar)
[![Supported PHP Versions](https://img.shields.io/packagist/dependency-v/konradmichalik/typo3-letter-avatar/php?logo=php)](https://packagist.org/packages/konradmichalik/typo3-letter-avatar)
![Stability](https://typo3-badges.dev/badge/typo3_letter_avatar/stability/shields.svg)
[![Coverage](https://img.shields.io/coverallsCoverage/github/jackd248/typo3-letter-avatar?logo=coveralls)](https://coveralls.io/github/jackd248/typo3-letter-avatar)
[![CGL](https://img.shields.io/github/actions/workflow/status/jackd248/typo3-letter-avatar/cgl.yml?label=cgl&logo=github)](https://github.com/jackd248/typo3-letter-avatar/actions/workflows/cgl.yml)
[![Tests](https://img.shields.io/github/actions/workflow/status/jackd248/typo3-letter-avatar/tests.yml?label=tests&logo=github)](https://github.com/jackd248/typo3-letter-avatar/actions/workflows/tests.yml)
[![License](https://poser.pugx.org/konradmichalik/typo3-letter-avatar/license)](LICENSE.md)
</div>

This TYPO3 extension generates colorful backend user avatars using name initials letter.

![user-list.jpg](Documentation/Images/user-list.jpg)

## ✨ Features

* Generates out-of-the-box colorful avatars for backend users
* Easily customizable and flexible configuration
* Provides different predefined color modes and themes
* Supports frontend user avatars with an additional viewhelper


## 🔥 Installation

### Requirements

* TYPO3 >= 11.5 
* PHP 8.1+

### Composer

[![Packagist](https://img.shields.io/packagist/v/konradmichalik/typo3-letter-avatar?label=version&logo=packagist)](https://packagist.org/packages/konradmichalik/typo3-letter-avatar)
[![Packagist Downloads](https://img.shields.io/packagist/dt/konradmichalik/typo3-letter-avatar?color=brightgreen)](https://packagist.org/packages/konradmichalik/typo3-letter-avatar)

``` bash
composer require konradmichalik/typo3-letter-avatar
```

### TER

[![TER version](https://typo3-badges.dev/badge/typo3_letter_avatar/version/shields.svg)](https://extensions.typo3.org/extension/typo3_letter_avatar)
[![TER downloads](https://typo3-badges.dev/badge/typo3_letter_avatar/downloads/shields.svg)](https://extensions.typo3.org/extension/typo3_letter_avatar)

Download the zip file from [TYPO3 extension repository (TER)](https://extensions.typo3.org/extension/typo3_letter_avatar).

### Setup

Set up the extension after the installation:

``` bash
vendor/bin/typo3 extension:setup --extension=typo3_letter_avatar
```

The extension will automatically generate avatars for all existing backend users.

## 🧰 Configuration

See [Configuration Documentation](Documentation/Configuration.md) for detailed setup instructions including:

* Extension settings configuration
* Custom themes and color modes
* Code-based configuration examples

## ⚡ Usage

See [Usage Documentation](Documentation/Usage.md) for comprehensive usage examples including:

* Backend user avatars (automatic)
* Programmatic avatar generation
* Fluid ViewHelper usage
* Console commands
* Event listener customization

## 🧑‍💻 Contributing

Please have a look at [`CONTRIBUTING.md`](CONTRIBUTING.md).

## 💎 Credits

This project is highly inspired by similar open source projects like [avatar](https://github.com/laravolt/avatar) and [letter-avatar](https://github.com/yohangdev/letter-avatar).

The fonts used in the extension are licensed under [SIL Open Font License](https://openfontlicense.org/) and [Apache License, Version 2.0](https://www.apache.org/licenses/LICENSE-2.0).


## ⭐ License

This project is licensed
under [GNU General Public License 2.0 (or later)](LICENSE.md).
