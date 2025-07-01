<?php

declare(strict_types=1);

namespace Laces\Commands;

use Laces\Actions\Prepare\CheckDependencies;
use Laces\Actions\Prepare\GetLatestLacesVersions;
use Laces\Actions\Prepare\GetLatestLaravelVersion;
use Laces\Actions\Prepare\GetLatestLivewireStarterKitVersion;
use Laces\Actions\Prepare\InstallLaravelWithLivewireStarterKit;
use Laces\Actions\Prepare\SetupWorkingFolder;
use Laces\Actions\Support\HandleError;
use Laces\Traits\Debuggable;
use Laces\Traits\Interfaceable;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
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

        $output->writeln("<info>Welcome to Laces. Who needs bootstraps?</>\n");

        // Check dependencies.
        $output->writeln('<info>[Laces]</> Checking dependencies...');
        $result = CheckDependencies::run();

        if ($result->hasError()) {
            return HandleError::run($result, $output);
        }

        // Get the latest versions.
        $laravelVersion = $this->laravelVersion();
        $livewireStarterKitVersion = $this->livewireStarterKitVersion();
        [$lacesLaravelVersion, $lacesLivewireStarterKitVersion] = $this->lacesVersions();

        $output->writeln('');
        $table = new Table($output);
        $table
            ->setHeaders(['Name', 'Latest Version', 'Laces Version'])
            ->setRows([
                ['Laravel', $laravelVersion, $lacesLaravelVersion],
                ['Livewire Starter Kit', $livewireStarterKitVersion, $lacesLivewireStarterKitVersion],
            ])
            ->render();
        $output->writeln('');

        $requiresBuild = ($laravelVersion !== $lacesLaravelVersion) || ($livewireStarterKitVersion !== $lacesLivewireStarterKitVersion);

        if (! $requiresBuild) {
            $output->writeln('<info>[Laces]</> <comment>Everything is up to date. No build required!</>');

            return Command::SUCCESS;
        }

        $output->writeln('<info>[Laces]</> <comment>Version mismatch detected. Continuing to build new Laces version.</>');

        // Setup the temporary working folder.
        $output->writeln('<info>[Laces]</> Setting up working folder...');
        $result = SetupWorkingFolder::run();

        if ($result->hasError()) {
            return HandleError::run($result, $output);
        }

        // Install Laravel.
        $output->writeln('<info>[Laces]</> Installing Laravel with the Livewire Starter Kit...');
        $result = InstallLaravelWithLivewireStarterKit::run();

        if ($result->hasError()) {
            return HandleError::run($result, $output);
        }

        return Command::SUCCESS;
    }

    /**
     * Handle getting the latest Laravel version.
     */
    protected function laravelVersion(): string
    {
        $this->output->writeln('<info>[Laces]</> Getting the latest Laravel version...');

        $result = GetLatestLaravelVersion::run();

        if ($result->hasError()) {
            return HandleError::run($result, $this->output);
        }

        return $result->version;
    }

    /**
     * Handle getting the latest Laravel version.
     */
    protected function livewireStarterKitVersion(): string
    {
        $this->output->writeln('<info>[Laces]</> Getting the latest Livewire Starter Kit version...');

        $result = GetLatestLivewireStarterKitVersion::run();

        if ($result->hasError()) {
            return HandleError::run($result, $this->output);
        }

        return $result->version;
    }

    /**
     * Handle getting the latest Laravel and Livewire Starter Kit versions used by Laces.
     */
    protected function lacesVersions(): array
    {
        $this->output->writeln('<info>[Laces]</> Getting the latest Laravel and Livewire Starter Kit versions used by Laces...');

        $result = GetLatestLacesVersions::run();

        if ($result->hasError()) {
            return HandleError::run($result, $this->output);
        }

        return [$result->laravelVersion, $result->livewireStarterKitVersion];
    }

    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this->setupDebuggable();
    }
}
