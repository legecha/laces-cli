<?php

declare(strict_types=1);

namespace Laces;

use Laces\Commands\BuildCommand;
use Laces\Commands\Prepare\CheckDependenciesCommand;
use Laces\Commands\Prepare\GetLatestLacesVersionsCommand;
use Laces\Commands\Prepare\GetLatestLaravelVersionCommand;
use Laces\Commands\Prepare\GetLatestLivewireStarterKitVersionCommand;
use Laces\Commands\Prepare\InstallLaravelWithLivewireStarterKitCommand;
use Laces\Commands\Prepare\SetupWorkingFolderCommand;
use Laces\Commands\Process\ConfigCommand;
use Laces\Commands\Process\DusterCommand;
use Laces\Commands\Process\EnforceStrictTypesCommand;
use Laces\Commands\Process\FluxCommand;
use Laces\Commands\Process\PasswordCommand;
use Laces\Commands\Process\PrettierCommand;
use Laces\Commands\Process\TestingCommand;
use Laces\Commands\Process\VersionCommand;
use Laces\Commands\Process\ViewsCommand;
use Laces\Commands\Process\WorkflowCommand;
use Symfony\Component\Console\Application;

class App extends Application
{
    public function __construct()
    {
        parent::__construct('Laces CLI', '1.0.0');

        $this->add(new BuildCommand);

        // Prepare.
        $this->add(new CheckDependenciesCommand);
        $this->add(new GetLatestLaravelVersionCommand);
        $this->add(new GetLatestLivewireStarterKitVersionCommand);
        $this->add(new GetLatestLacesVersionsCommand);
        $this->add(new SetupWorkingFolderCommand);
        $this->add(new InstallLaravelWithLivewireStarterKitCommand);

        // Process.
        $this->add(new EnforceStrictTypesCommand);
        $this->add(new ConfigCommand);
        $this->add(new TestingCommand);
        $this->add(new PasswordCommand);
        $this->add(new WorkflowCommand);
        $this->add(new FluxCommand);
        $this->add(new DusterCommand);
        $this->add(new PrettierCommand);
        $this->add(new ViewsCommand);
        $this->add(new VersionCommand);
    }
}
