<?php

declare(strict_types=1);

namespace Laces\Traits;

use Symfony\Component\Console\Input\InputOption;

trait Debuggable
{
    /**
     * Output some data, only when in debug mode.
     */
    protected function debug(mixed $data): void
    {
        if ($this->input->getOption('debug') !== true) {
            return;
        }

        $this->output->writeln(print_r($data, true));
    }

    /**
     * Setup the --debug option.
     */
    protected function setupDebuggable(): void
    {
        $this->addOption(
            'debug',
            null,
            InputOption::VALUE_NONE,
            'Enable debug output'
        );
    }
}
