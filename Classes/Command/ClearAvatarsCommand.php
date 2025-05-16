<?php

declare(strict_types=1);

namespace KonradMichalik\Typo3LetterAvatar\Command;

use KonradMichalik\Typo3LetterAvatar\Utility\PathUtility;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

final class ClearAvatarsCommand extends Command
{
    protected function configure(): void
    {
        $this->setHelp('Clear all generated letter avatars.')
            ->addOption(
                'dry-run',
                null,
                InputOption::VALUE_NONE,
                'Simulate the deletion process without actually deleting files.'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $path = PathUtility::getImageFolder();
        $output->writeln("🧽 - Clearing all generated letter avatars in <question>$path</question>");

        $imageCount = 0;
        if (is_dir($path)) {
            $files = scandir($path);
            $imageCount = count(array_filter($files, function ($file) use ($path) {
                return is_file($path . DIRECTORY_SEPARATOR . $file) && preg_match('/\.(png|jpg|jpeg)$/i', $file);
            }));
        }

        if ($input->getOption('dry-run')) {
            $output->writeln("ℹ️ - <comment>$imageCount</comment> letter avatars would be cleared (dry-run).");
            return Command::SUCCESS;
        }

        $return = GeneralUtility::rmdir($path, true);

        if ($return === false) {
            $output->writeln('❌ - Failed to clear generated letter avatars.');
            return Command::FAILURE;
        }

        $output->writeln("✅  - <comment>$imageCount</comment> letter avatars have been cleared.");

        return Command::SUCCESS;
    }
}
