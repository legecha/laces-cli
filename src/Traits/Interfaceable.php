<?php

declare(strict_types=1);

namespace Laces\Traits;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

trait Interfaceable
{
    /**
     * The input interface.
     */
    protected InputInterface $input;

    /**
     * The output interface.
     */
    protected OutputInterface $output;
}
