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
        $installDir = __DIR__.'/../../../.working/install/';

        // Allow attempted commits based on the staged state.
        // When clean, `git diff --cached --quiet` returns 0 (success), meaning no staged changes.
        // When dirty (i.e., staged changes exist), it returns 1 (failure), and we proceed with the commit.
        if ($command === Git::MaybeCommit) {
            $check = new Process(['git', 'diff', '--cached', '--quiet']);
            $check->setWorkingDirectory($installDir);
            $check->run();

            if ($check->isSuccessful()) {
                return;
            }

            $command = Git::Commit;
        }

        $process = match ($command) {
            Git::Init => new Process(['git', 'init']),
            Git::Add => new Process(['git', 'add', '.']),
            Git::Commit => new Process(['git', 'commit', '-m', $message]),
        };

        $process->setWorkingDirectory($installDir);
        $process->mustRun();
    }
}
