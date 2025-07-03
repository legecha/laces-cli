<?php

declare(strict_types=1);

namespace Laces\Actions\Support;

use Laces\Enums\Git;
use Symfony\Component\Process\Process;

class PerformGitCommand
{
    /**
     * Perform Git commands.
     */
    public static function run(Git $command, ?string $message = null): void
    {
        $process = match ($command) {
            Git::Init => new Process(['git', 'init']),
            Git::Add => new Process(['git', 'add', '.']),
            Git::Commit => new Process(['git', 'commit', '-m', $message]),
        };

        $process->setWorkingDirectory(__DIR__.'/../../../.working/install');
        $process->mustRun();
    }
}
