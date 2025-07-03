<?php

declare(strict_types=1);

namespace Laces\Commands\Prepare;

use Laces\Actions\Prepare\InstallLaravelWithLivewireStarterKit;
use Laces\Actions\Support\HandleError;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CLI command to install Laravel with the Livewire Starter Kit into the .working directory.
 */
#[AsCommand(
    name: 'prepare:install',
    description: 'Installs Laravel with Livewire Starter Kit to the .working directory'
)]
class InstallLaravelWithLivewireStarterKitCommand extends Command
{
    /**
     * Executes the command.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Installing...</info>');

        $result = InstallLaravelWithLivewireStarterKit::run();

        if ($result->hasError()) {
            return HandleError::run($result, $output);
        }

        $output->writeln("\n<comment>Laravel installed successfully.</>");

        return Command::SUCCESS;
    }
}
