<?php

declare(strict_types=1);

namespace Laces\Commands\Process;

use Laces\Actions\Process\Config;
use Laces\Actions\Support\HandleError;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CLI command to setup config.
 */
#[AsCommand(
    name: 'process:config',
    description: 'Setup config'
)]
class ConfigCommand extends Command
{
    /**
     * Executes the command.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Setting up config...</info>');

        $result = Config::run();

        if ($result->hasError()) {
            return HandleError::run($result, $output);
        }

        $output->writeln("\n<comment>Config setup successfully.</>");

        return Command::SUCCESS;
    }
}
