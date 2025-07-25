<?php

declare(strict_types=1);

namespace Laces\Commands\Prepare;

use Laces\Actions\Prepare\CheckDependencies;
use Laces\Actions\Support\HandleError;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CLI command to verify required external dependencies.
 */
#[AsCommand(
    name: 'prepare:dependencies',
    description: 'Checks that required dependencies are installed'
)]
class CheckDependenciesCommand extends Command
{
    /**
     * Executes the command.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Checking dependencies...</info>');

        $result = CheckDependencies::run();

        if ($result->hasError()) {
            return HandleError::run($result, $output);
        }

        $output->writeln("\n<comment>All dependencies are installed.</>");

        return Command::SUCCESS;
    }
}
