<?php

declare(strict_types=1);

namespace Laces\Commands\Process;

use Laces\Actions\Process\Duster;
use Laces\Actions\Support\HandleError;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CLI command to install Duster.
 */
#[AsCommand(
    name: 'process:duster',
    description: 'Install Duster'
)]
class DusterCommand extends Command
{
    /**
     * Executes the command.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Installing Duster...</info>');

        $result = Duster::run();

        if ($result->hasError()) {
            return HandleError::run($result, $output);
        }

        $output->writeln("\n<comment>Successfully installed Duster.</>");

        return Command::SUCCESS;
    }
}
