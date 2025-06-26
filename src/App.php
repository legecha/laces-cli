<?php

declare(strict_types=1);

namespace Laces;

use Laces\Commands\BuildCommand;
use Laces\Commands\CheckDependenciesCommand;
use Laces\Commands\GetLatestLacesVersionsCommand;
use Laces\Commands\GetLatestLaravelVersionCommand;
use Laces\Commands\GetLatestLivewireStarterKitVersionCommand;
use Symfony\Component\Console\Application;

class App extends Application
{
    public function __construct()
    {
        parent::__construct('Laces CLI', '1.0.0');

        $this->add(new BuildCommand);
        $this->add(new CheckDependenciesCommand);
        $this->add(new GetLatestLaravelVersionCommand);
        $this->add(new GetLatestLivewireStarterKitVersionCommand);
        $this->add(new GetLatestLacesVersionsCommand);
    }
}
