<?php

declare(strict_types=1);

namespace Laces\Actions\Process;

use Laces\DataTransferObjects\Process\EnforceStrictTypesDto;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;
use RuntimeException;
use Symfony\Component\Filesystem\Filesystem;
use Throwable;

class EnforceStrictTypes
{
    /**
     * The directories to target.
     */
    protected static array $targetDirs = [
        'app',
        'bootstrap',
        'config',
        'database',
        'public',
        'routes',
        'tests',
    ];

    /**
     * Enforce strict types on all PHP files.
     */
    public static function run(): EnforceStrictTypesDto
    {
        $fs = new Filesystem;
        $installDir = __DIR__.'/../../../.working/install/';

        try {
            // Add strict types.
            foreach (self::$targetDirs as $dir) {
                $iterator = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($installDir.$dir, RecursiveDirectoryIterator::SKIP_DOTS)
                );
                $phpFiles = new RegexIterator($iterator, '/\.php$/');

                foreach ($phpFiles as $file) {
                    $path = $file->getPathname();

                    $contents = file_get_contents($path);
                    if ($contents === false) {
                        throw new RuntimeException("Unable to read file: $path");
                    }

                    if (str_contains($contents, 'declare(strict_types=1);')) {
                        continue;
                    }

                    if (preg_match('/^<\?php/', $contents, $matches)) {
                        $replacement = $matches[0]."\n\ndeclare(strict_types=1);\n\n";
                        $newContents = preg_replace('/^<\?php\s*/', $replacement, $contents, 1);

                        $fs->dumpFile($path, $newContents);
                    }
                }
            }

            // Fix any files that fail strict types.
            $path = $installDir.'app/Livewire/Auth/ResetPassword.php';
            $contents = file_get_contents($path);
            if ($contents === false) {
                throw new RuntimeException("Unable to read file: $path");
            }

            $newContents = str_replace(
                '$this->email = request()->string(\'email\');',
                '$this->email = (string) request()->string(\'email\');',
                $contents
            );

            $fs->dumpFile($path, $newContents);
        } catch (Throwable $t) {
            return new EnforceStrictTypesDto(
                result: false,
                errors: [$t->getMessage()],
            );
        }

        return new EnforceStrictTypesDto(
            result: true,
        );
    }
}
