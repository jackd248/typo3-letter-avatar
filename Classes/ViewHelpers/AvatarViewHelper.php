<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\ViewHelpers;

use KonradMichalik\Typo3LetterAvatar\Enum\ColorMode;
use KonradMichalik\Typo3LetterAvatar\Enum\ImageFormat;
use KonradMichalik\Typo3LetterAvatar\Enum\Transform;
use KonradMichalik\Typo3LetterAvatar\Image\Avatar;
use KonradMichalik\Typo3LetterAvatar\Utility\ConfigurationUtility;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
* This ViewHelper generates the URL of an avatar image based on the provided configuration.
* You can specify properties such as name, initials, size, font, colors and transformation.
* If the avatar image does not already exist, it will be generated and saved.
* Useful for generating letter avatars for TYPO3 frontend users.
*
* Example usage:
* ```html
* <html xmlns:letter="http://typo3.org/ns/KonradMichalik/Typo3LetterAvatar/ViewHelpers">
*
* <img src="{letter:avatar(name: 'John Doe')}" alt="Avatar of John Doe" />
* ```
*/
class AvatarViewHelper extends AbstractViewHelper
{
    public function initializeArguments(): void
    {
        $this->registerArgument(
            'name',
            'string',
            'Name for the avatar',
            false
        );
        $this->registerArgument(
            'initials',
            'string',
            'Initials for the avatar',
            false
        );
        $this->registerArgument(
            'size',
            'integer',
            'Size of the avatar',
            false
        );
        $this->registerArgument(
            'fontSize',
            'float',
            'Font size of the avatar',
            false
        );
        $this->registerArgument(
            'fontPath',
            'string',
            'Path to the font file',
            false
        );
        $this->registerArgument(
            'foregroundColor',
            'string',
            'Foreground color of the avatar',
            false
        );
        $this->registerArgument(
            'backgroundColor',
            'string',
            'Background color of the avatar',
            false
        );
        $this->registerArgument(
            'mode',
            'string',
            'Color mode of the avatar (e.g., CUSTOM)',
            false
        );
        $this->registerArgument(
            'theme',
            'string',
            'Theme of the avatar',
            false
        );
        $this->registerArgument(
            'imageFormat',
            'string',
            'Image format of the avatar (e.g., PNG)',
            false
        );
        $this->registerArgument(
            'transform',
            'string',
            'Text transformation (e.g., NONE)',
            false
        );
    }

    public function render(): string
    {
        if (empty($this->arguments['name']) && empty($this->arguments['initials'])) {
            throw new \InvalidArgumentException('Either name or initials must be provided', 1204028706);
        }

        $configuration = [
            'name' => $this->arguments['name'] ?: '',
            'initials' => $this->arguments['initials'] ?: '',
            'mode' => $this->arguments['mode'] ? ColorMode::tryFrom($this->arguments['mode']) : ConfigurationUtility::get('mode', ColorMode::class),
            'theme' => $this->arguments['theme'] ?: ConfigurationUtility::get('theme'),
            'size' => $this->arguments['size'] ?: ConfigurationUtility::get('size'),
            'fontSize' => $this->arguments['fontSize'] ?: ConfigurationUtility::get('fontSize'),
            'fontPath' => $this->arguments['fontPath'] ?: ConfigurationUtility::get('fontPath'),
            'imageFormat' => $this->arguments['imageFormat'] ? ImageFormat::tryFrom($this->arguments['imageFormat']) : ConfigurationUtility::get('imageFormat', ImageFormat::class),
            'transform' => $this->arguments['transform'] ? Transform::tryFrom($this->arguments['transform']) : ConfigurationUtility::get('transform', Transform::class),
        ];

        $avatarService = Avatar::create(...$configuration);

        if (!file_exists($avatarService->getImagePath())) {
            $avatarService->save();
        }

        return $avatarService->getWebPath();
    }
}
