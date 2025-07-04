<?php

declare(strict_types=1);

namespace Laces\Commands\Process;

use Laces\Actions\Process\EnforceStrictTypes;
use Laces\Actions\Support\HandleError;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CLI command to enforce strict types on all PHP files.
 */
#[AsCommand(
    name: 'process:strict-types',
    description: 'Enforce strict types on all PHP files'
)]
class EnforceStrictTypesCommand extends Command
{
    /**
     * Executes the command.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Enforcing strict types on all PHP files...</info>');

        $result = EnforceStrictTypes::run();

        if ($result->hasError()) {
            return HandleError::run($result, $output);
        }

        $output->writeln("\n<comment>Strict types enforced on all PHP files.</>");

        return Command::SUCCESS;
    }
}
