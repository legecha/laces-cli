<?php

declare(strict_types=1);

namespace Laces\Commands\Process;

use Laces\Actions\Process\Workflow;
use Laces\Actions\Support\HandleError;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CLI command to remove the GitHub workflows.
 */
#[AsCommand(
    name: 'process:workflows',
    description: 'Remove the GitHub workflows'
)]
class WorkflowCommand extends Command
{
    /**
     * Executes the command.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Removing the GitHub workflows...</info>');

        $result = Workflow::run();

        if ($result->hasError()) {
            return HandleError::run($result, $output);
        }

        $output->writeln("\n<comment>Successfully removed the GitHub workflows.</>");

        return Command::SUCCESS;
    }
}
