<?php

declare(strict_types=1);

namespace Laces\Commands\Process;

use Laces\Actions\Process\Views;
use Laces\Actions\Support\HandleError;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CLI command to improve the default views.
 */
#[AsCommand(
    name: 'process:views',
    description: 'Improve default views'
)]
class ViewsCommand extends Command
{
    /**
     * Executes the command.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Improving default views...</info>');

        $result = Views::run();

        if ($result->hasError()) {
            return HandleError::run($result, $output);
        }

        $output->writeln("\n<comment>Successfully improved default views.</>");

        return Command::SUCCESS;
    }
}
