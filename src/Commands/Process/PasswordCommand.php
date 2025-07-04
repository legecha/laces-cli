<?php

declare(strict_types=1);

namespace Laces\Commands\Process;

use Laces\Actions\Process\Password;
use Laces\Actions\Support\HandleError;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CLI command to strengthen the password requirements.
 */
#[AsCommand(
    name: 'process:password',
    description: 'Strengthen password requirements'
)]
class PasswordCommand extends Command
{
    /**
     * Executes the command.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Strengthening password requirements...</info>');

        $result = Password::run();

        if ($result->hasError()) {
            return HandleError::run($result, $output);
        }

        $output->writeln("\n<comment>Strengthened password requirements successfully.</>");

        return Command::SUCCESS;
    }
}
