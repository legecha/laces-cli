<?php

declare(strict_types=1);

namespace Laces\Actions\Process;

use Laces\Actions\Support\ReplaceContentsInFile;
use Laces\DataTransferObjects\Process\DusterDto;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Throwable;

class Duster
{
    /**
     * The new composer scripts JSON.
     */
    public static string $composerScripts = <<<'PHP'
],
        "fix": "duster fix",
        "lint": "duster lint"
    },
    "extra": {
PHP;

    /**
     * Install Duster.
     */
    public static function run(): DusterDto
    {
        try {
            $fs = new Filesystem;
            $installDir = __DIR__.'/../../../.working/install/';

            Process::fromShellCommandline('composer require tightenco/duster --dev')
                ->setWorkingDirectory($installDir)
                ->setTimeout(300)
                ->mustRun();

            $fs->dumpFile(
                $installDir.'.php-cs-fixer.dist.php',
                file_get_contents(__DIR__.'/../../../stubs/.php-cs-fixer.dist.php'),
            );

            ReplaceContentsInFile::run(
                'composer.json',
                "]\n    },\n    \"extra\": {",
                static::$composerScripts,
            );

            // Fixes that Duster will error on.
            foreach ([
                'app/Livewire/Auth/Register.php',
                'app/Livewire/Auth/ResetPassword.php',
            ] as $file) {
                foreach ([
                    'use Illuminate\\Validation\\Rules;' => 'use Illuminate\\Validation\\Rules\\Password as RulesPassword;',
                    'Rules\\Password::defaults()' => 'RulesPassword::defaults()',
                ] as $search => $replace) {
                    ReplaceContentsInFile::run($file, $search, $replace);
                }
            }

            Process::fromShellCommandline('composer fix')
                ->setWorkingDirectory($installDir)
                ->setTimeout(300)
                ->mustRun();
        } catch (Throwable $t) {
            return new DusterDto(
                result: false,
                errors: [$t->getMessage()],
            );
        }

        return new DusterDto(
            result: true,
        );
    }
}
