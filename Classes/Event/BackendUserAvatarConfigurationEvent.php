<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Event;

class BackendUserAvatarConfigurationEvent
{
    final public const NAME = 'typo3_letter_avatar.backend_user.modify_avatar_provider';

    public function __construct(
        protected array $backendUser,
        protected array $configuration
    ) {
    }

    public function getBackendUser(): array
    {
        return $this->backendUser;
    }

    public function getConfiguration(): array
    {
        return $this->configuration;
    }

    public function setConfiguration(array $configuration): void
    {
        $this->configuration = $configuration;
    }
}
