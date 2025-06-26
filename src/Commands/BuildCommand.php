<?php

declare(strict_types=1);

namespace Laces\Commands;

use Laces\Actions\HandleError;
use Laces\Traits\Debuggable;
use Laces\Traits\Interfaceable;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'build',
    description: 'Builds and publishes the Laces starter kit'
)]
class BuildCommand extends Command
{
    use Debuggable,
        Interfaceable;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;

        $output->writeln('<info>Welcome to Laces. Who needs bootstraps?</>');

        // Actions to run.
        $actions = [
            'CheckDependencies' => 'checking dependencies',
            'GetLatestLaravelVersion' => 'getting latest Laravel version',
            'GetLatestLivewireStarterKitVersion' => 'getting latest Livewire Starter Kit version',
            'GetLatestLacesVersions' => 'getting latest Laces versions',
        ];

        foreach ($actions as $action => $message) {
            $output->writeln("<info>[Laces]</> $message...");

            $result = "Laces\\Actions\\$action"::run();

            if ($result->hasError()) {
                return HandleError::run($result, $output);
            }

            $this->debug($result);
        }

        // Placeholder — logic will come next
        return Command::SUCCESS;
    }

    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this->setupDebuggable();
    }
}
