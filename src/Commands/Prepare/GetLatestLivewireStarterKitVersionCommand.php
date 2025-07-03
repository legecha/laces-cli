<?php

declare(strict_types=1);

namespace Laces\Commands\Prepare;

use Laces\Actions\Prepare\GetLatestLivewireStarterKitVersion;
use Laces\Actions\Support\HandleError;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CLI command to get the latest Livewire Starter Kit version.
 */
#[AsCommand(
    name: 'prepare:starter-kit-version',
    description: 'Get the latest Livewire Starter Kit version'
)]
class GetLatestLivewireStarterKitVersionCommand extends Command
{
    /**
     * Executes the command.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Getting the latest Livewire Starter Kit version...</>');

        $result = GetLatestLivewireStarterKitVersion::run();

        if ($result->hasError()) {
            return HandleError::run($result, $output);
        }

        $output->writeln("\n<comment>{$result->version}</>");

        return Command::SUCCESS;
    }
}
