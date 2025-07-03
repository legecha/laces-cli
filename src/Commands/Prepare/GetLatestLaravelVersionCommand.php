<?php

declare(strict_types=1);

namespace Laces\Commands\Prepare;

use Laces\Actions\Prepare\GetLatestLaravelVersion;
use Laces\Actions\Support\HandleError;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CLI command to get the latest Laravel version.
 */
#[AsCommand(
    name: 'prepare:laravel-version',
    description: 'Get the latest Laravel version'
)]
class GetLatestLaravelVersionCommand extends Command
{
    /**
     * Executes the command.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Getting the latest Laravel version...</>');

        $result = GetLatestLaravelVersion::run();

        if ($result->hasError()) {
            return HandleError::run($result, $output);
        }

        $output->writeln("\n<comment>{$result->version}</>");

        return Command::SUCCESS;
    }
}
