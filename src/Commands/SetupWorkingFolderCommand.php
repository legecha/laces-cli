<?php

declare(strict_types=1);

namespace Laces\Commands;

use Laces\Actions\HandleError;
use Laces\Actions\SetupWorkingFolder;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CLI command to setup the temporary working folder for a fresh Laravel and Livewire Starter Kit installation.
 */
#[AsCommand(
    name: 'working-folder',
    description: 'Sets up the temporary working folder'
)]
class SetupWorkingFolderCommand extends Command
{
    /**
     * Executes the command.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $result = SetupWorkingFolder::run();

        if ($result->hasError()) {
            return HandleError::run($result, $output);
        }

        foreach ($result->messages as $message) {
            $output->writeln("\n<info>$message</>");
        }

        return Command::SUCCESS;
    }
}
