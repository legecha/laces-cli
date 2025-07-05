<?php

declare(strict_types=1);

namespace Laces\Commands\Process;

use Laces\Actions\Process\Flux;
use Laces\Actions\Support\HandleError;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * CLI command to install Flux Pro.
 */
#[AsCommand(
    name: 'process:flux',
    description: 'Install Flux Pro'
)]
class FluxCommand extends Command
{
    /**
     * Executes the command.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        if (! $io->confirm('Would you like to install Flux Pro?')) {
            return Command::SUCCESS;
        }

        $output->writeln("<info>Please visit https://fluxui.dev/dashboard to obtain your license key.</>\n");

        $email = trim($io->ask('Flux email:', ''));
        $key = trim($io->ask('Flux Pro Key:', ''));

        $output->writeln('<info>Installing Flux UI Pro...');
        $result = Flux::run($email, $key);

        if ($result->hasError()) {
            return HandleError::run($result, $output);
        }

        $output->writeln("\n<comment>Successfully installed Flux Pro.</>");

        return Command::SUCCESS;
    }
}
