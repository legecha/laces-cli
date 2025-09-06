<?php

declare(strict_types=1);

namespace Laces\Commands\Process;

use Laces\Actions\Prepare\GetLatestLaravelVersion;
use Laces\Actions\Prepare\GetLatestLivewireStarterKitVersion;
use Laces\Actions\Process\Version;
use Laces\Actions\Support\HandleError;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * CLI command to update Laces versions.
 */
#[AsCommand(
    name: 'process:version',
    description: 'Update Laces versions'
)]
class VersionCommand extends Command
{
    /**
     * Executes the command.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('<info>Updating Laces versions...</info>');

        $result = Version::run(
            GetLatestLaravelVersion::run()->version,
            GetLatestLivewireStarterKitVersion::run()->version,
        );

        if ($result->hasError()) {
            return HandleError::run($result, $output);
        }

        $output->writeln("\n<comment>Successfully updated Laces versions.</>");

        return Command::SUCCESS;
    }
}
