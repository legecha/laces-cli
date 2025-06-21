<?php

namespace Laces;

use Symfony\Component\Console\Application;
use Laces\Command\BuildCommand;

class App extends Application
{
    public function __construct()
    {
        parent::__construct('Laces CLI', '1.0.0');

        $this->add(new BuildCommand());
    }
}
