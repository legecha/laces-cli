<?php

declare(strict_types=1);

namespace Laces\Commands;

use Laces\Actions\Prepare\CheckDependencies;
use Laces\Actions\Prepare\GetLatestLacesVersions;
use Laces\Actions\Prepare\GetLatestLaravelVersion;
use Laces\Actions\Prepare\GetLatestLivewireStarterKitVersion;
use Laces\Actions\Prepare\InstallLaravelWithLivewireStarterKit;
use Laces\Actions\Prepare\SetupWorkingFolder;
use Laces\Actions\Process\Config;
use Laces\Actions\Process\Duster;
use Laces\Actions\Process\EnforceStrictTypes;
use Laces\Actions\Process\Password;
use Laces\Actions\Process\Prettier;
use Laces\Actions\Process\Testing;
use Laces\Actions\Process\Version;
use Laces\Actions\Process\Views;
use Laces\Actions\Process\Workflow;
use Laces\Actions\Support\HandleError;
use Laces\Actions\Support\PerformGitCommand;
use Laces\DataTransferObjects\Process\FluxDto;
use Laces\Enums\Git;
use Laces\Traits\Debuggable;
use Laces\Traits\Interfaceable;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

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

        $this->output->writeln("<info>Welcome to Laces. Who needs bootstraps?</>\n");

        // Check dependencies.
        $this->output->writeln('<info>[Laces]</> Checking dependencies...');
        $result = CheckDependencies::run();

        if ($result->hasError()) {
            return HandleError::run($result, $this->output);
        }

        // Get the latest versions.
        $laravelVersion = $this->laravelVersion();
        $livewireStarterKitVersion = $this->livewireStarterKitVersion();
        [$lacesLaravelVersion, $lacesLivewireStarterKitVersion] = $this->lacesVersions();

        $this->output->writeln('');
        $table = new Table($this->output);
        $table
            ->setHeaders(['Name', 'Latest Version', 'Laces Version'])
            ->setRows([
                ['Laravel', $laravelVersion, $lacesLaravelVersion],
                ['Livewire Starter Kit', $livewireStarterKitVersion, $lacesLivewireStarterKitVersion],
            ])
            ->render();
        $this->output->writeln('');

        $requiresBuild = ($laravelVersion !== $lacesLaravelVersion) || ($livewireStarterKitVersion !== $lacesLivewireStarterKitVersion);

        if (! $requiresBuild) {
            $this->output->writeln('<info>[Laces]</> <comment>Everything is up to date. No build required!</>');

            return Command::SUCCESS;
        }

        $this->output->writeln('<info>[Laces]</> <comment>Version mismatch detected. Continuing to build new Laces version.</>');

        // Setup the temporary working folder.
        $this->output->writeln('<info>[Laces]</> Setting up working folder...');
        $result = SetupWorkingFolder::run();

        if ($result->hasError()) {
            return HandleError::run($result, $this->output);
        }

        // Install Laravel.
        $this->output->writeln('<info>[Laces]</> Installing Laravel with the Livewire Starter Kit...');
        $result = InstallLaravelWithLivewireStarterKit::run();

        if ($result->hasError()) {
            return HandleError::run($result, $this->output);
        }

        $result = $this->git(
            Git::Init,
            Git::Add,
            [Git::Commit, 'Install Laravel with the Livewire Starter Kit'],
        );
        if ($result !== Command::SUCCESS) {
            return $result;
        }

        // Enforce strict types.
        $this->output->writeln('<info>[Laces]</> Enforcing strict types...');
        $result = EnforceStrictTypes::run();

        if ($result->hasError()) {
            return HandleError::run($result, $this->output);
        }

        $result = $this->git(
            Git::Add,
            [Git::Commit, 'Enforce strict types'],
        );
        if ($result !== Command::SUCCESS) {
            return $result;
        }

        // Setup config.
        $this->output->writeln('<info>[Laces]</> Setting up config...');
        $result = Config::run();

        if ($result->hasError()) {
            return HandleError::run($result, $this->output);
        }

        $result = $this->git(
            Git::Add,
            [Git::Commit, 'Setup config'],
        );
        if ($result !== Command::SUCCESS) {
            return $result;
        }

        // Improve testing setup.
        $this->output->writeln('<info>[Laces]</> Improving testing setup...');
        $result = Testing::run();

        if ($result->hasError()) {
            return HandleError::run($result, $this->output);
        }

        $result = $this->git(
            Git::Add,
            [Git::Commit, 'Improve testing setup'],
        );
        if ($result !== Command::SUCCESS) {
            return $result;
        }

        // Strengthen password requirements.
        $this->output->writeln('<info>[Laces]</> Strengthening password requirements...');
        $result = Password::run();

        if ($result->hasError()) {
            return HandleError::run($result, $this->output);
        }

        $result = $this->git(
            Git::Add,
            [Git::Commit, 'Strengthened password requirements'],
        );
        if ($result !== Command::SUCCESS) {
            return $result;
        }

        // Remove GitHub workflows.
        $this->output->writeln('<info>[Laces]</> Removing GitHub workflows...');
        $result = Workflow::run();

        if ($result->hasError()) {
            return HandleError::run($result, $this->output);
        }

        $result = $this->git(
            Git::Add,
            [Git::Commit, 'Remove GitHub workflows'],
        );
        if ($result !== Command::SUCCESS) {
            return $result;
        }

        // Install Flux Pro.
        $result = $this->getApplication()->doRun(new ArrayInput([
            'command' => 'process:flux',
        ]), $this->output);

        if ($result !== Command::SUCCESS) {
            return HandleError::run(new FluxDto(
                result: false,
                errors: ['Could not install Flux pro'],
            ), $this->output);
        }

        $result = $this->git(
            Git::Add,
            [Git::MaybeCommit, 'Install Flux Pro'],
        );
        if ($result !== Command::SUCCESS) {
            return $result;
        }

        // Improve default views.
        $this->output->writeln('<info>[Laces]</> Improving default views...');
        $result = Views::run();

        if ($result->hasError()) {
            return HandleError::run($result, $this->output);
        }

        $result = $this->git(
            Git::Add,
            [Git::Commit, 'Improve default views'],
        );
        if ($result !== Command::SUCCESS) {
            return $result;
        }

        // Install Duster.
        $this->output->writeln('<info>[Laces]</> Install Duster...');
        $result = Duster::run();

        if ($result->hasError()) {
            return HandleError::run($result, $this->output);
        }

        $result = $this->git(
            Git::Add,
            [Git::Commit, 'Install Duster'],
        );
        if ($result !== Command::SUCCESS) {
            return $result;
        }

        // Install Prettier.
        $this->output->writeln('<info>[Laces]</> Install Prettier...');
        $result = Prettier::run();

        if ($result->hasError()) {
            return HandleError::run($result, $this->output);
        }

        $result = $this->git(
            Git::Add,
            [Git::Commit, 'Install Prettier'],
        );
        if ($result !== Command::SUCCESS) {
            return $result;
        }

        // Update Laces versions.
        $this->output->writeln('<info>[Laces]</> Updating Laces versions...');
        $result = Version::run($laravelVersion, $livewireStarterKitVersion);

        if ($result->hasError()) {
            return HandleError::run($result, $this->output);
        }

        $result = $this->git(
            Git::Add,
            [Git::Commit, 'Update Laces versions'],
        );
        if ($result !== Command::SUCCESS) {
            return $result;
        }

        return Command::SUCCESS;
    }

    /**
     * Perform Git commands.
     *
     * @param  array<int, Laces\Enums\Git|array<Laces\Enums\Git, string>>  ...$commands
     */
    protected function git(Git|array ...$commands): ?int
    {
        try {
            foreach ($commands as $command) {
                if ($command instanceof Git) {
                    PerformGitCommand::run($command);
                } else {
                    PerformGitCommand::run($command[0], $command[1]);
                }
            }
        } catch (Throwable $t) {
            $this->output->writeln('<info>[Laces]</> <error>Could not perform Git command: '.$t->getMessage());

            return Command::FAILURE;
        }

        $this->output->writeln('<info>[Laces]</> <comment>Git commands performed.</>');

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
