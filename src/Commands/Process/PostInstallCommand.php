<?php

declare(strict_types=1);

namespace Laces\Commands\Process;

use Laces\Actions\Process\PostInstall;
use Laces\Actions\Support\HandleError;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CLI command to setup the post install script.
 */
#[AsCommand(
    name: 'process:post-install',
    description: 'Setup post-install script'
)]
class PostInstallCommand extends Command
{
    /**
     * Executes the command.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Setting up post-install script...</info>');

        $result = PostInstall::run();

        if ($result->hasError()) {
            return HandleError::run($result, $output);
        }

        $output->writeln("\n<comment>Successfully setup post-install script.</>");

        return Command::SUCCESS;
    }
}
