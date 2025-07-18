<?php

declare(strict_types=1);

namespace Laces\Actions\Support;

use Laces\Contracts\Error;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;

class HandleError
{
    /**
     * Handle message output and return codes for errors.
     */
    public static function run(Error $errorResult, OutputInterface $output): int
    {
        $output->writeln('<error>'.$errorResult->errorMessage().'</>');

        return Command::FAILURE;
    }
}
