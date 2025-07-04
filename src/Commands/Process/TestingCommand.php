<?php

declare(strict_types=1);

namespace Laces\Commands\Process;

use Laces\Actions\Process\Testing;
use Laces\Actions\Support\HandleError;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CLI command to tidy up test files.
 */
#[AsCommand(
    name: 'process:testing',
    description: 'Improve testing setup'
)]
class TestingCommand extends Command
{
    /**
     * Executes the command.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Improving testing setup...</info>');

        $result = Testing::run();

        if ($result->hasError()) {
            return HandleError::run($result, $output);
        }

        $output->writeln("\n<comment>Testing setup improved successfully.</>");

        return Command::SUCCESS;
    }
}
