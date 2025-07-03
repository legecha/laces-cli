<?php

declare(strict_types=1);

namespace Laces\Actions\Prepare;

use Laces\DataTransferObjects\Prepare\CheckDependenciesDto;
use Symfony\Component\Process\Process;

class CheckDependencies
{
    /**
     * Check system dependencies for the application.
     */
    public static function run(): CheckDependenciesDto
    {
        $missing = [];

        if (! self::commandIsAvailable('laravel')) {
            $missing[] = 'laravel (Laravel installer)';
        }

        if (! self::commandIsAvailable('git')) {
            $missing[] = 'git (Git cli)';
        }

        return new CheckDependenciesDto(
            result: empty($missing),
            missing: $missing ?: null
        );
    }

    /**
     * Check if a given command is available on the system.
     */
    protected static function commandIsAvailable(string $command): bool
    {
        $process = Process::fromShellCommandline("which $command");
        $process->run();

        return $process->isSuccessful();
    }
}
