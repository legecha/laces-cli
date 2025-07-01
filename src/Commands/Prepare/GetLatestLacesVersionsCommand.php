<?php

declare(strict_types=1);

namespace Laces\Commands\Prepare;

use Laces\Actions\Prepare\GetLatestLacesVersions;
use Laces\Actions\Support\HandleError;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CLI command to get the latest Laravel and Livewire Starter Kit versions used by Laces.
 */
#[AsCommand(
    name: 'laces-versions',
    description: 'Get the latest Laravel and Livewire Starter Kit versions that Laces uses'
)]
class GetLatestLacesVersionsCommand extends Command
{
    /**
     * Executes the command.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Getting the latest Laravel and Livewire Starter Kit versions Laces is currently using...</>');

        $result = GetLatestLacesVersions::run();

        if ($result->hasError()) {
            return HandleError::run($result, $output);
        }

        $output->writeln("\n<comment>Laravel: {$result->laravelVersion}</>");
        $output->writeln("<comment>Laces: {$result->livewireStarterKitVersion}</>");

        return Command::SUCCESS;
    }
}
