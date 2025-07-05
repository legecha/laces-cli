<?php

declare(strict_types=1);

namespace Laces\Actions\Process;

use Laces\Actions\Support\ReplaceContentsInFile;
use Laces\DataTransferObjects\Process\PrettierDto;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Throwable;

class Prettier
{
    /**
     * Install Prettier.
     */
    public static function run(): PrettierDto
    {
        try {
            $fs = new Filesystem;
            $installDir = __DIR__.'/../../../.working/install/';

            Process::fromShellCommandline('npm install --save-dev prettier-plugin-blade@^2 prettier prettier-plugin-tailwindcss')
                ->setWorkingDirectory($installDir)
                ->setTimeout(300)
                ->mustRun();

            foreach ([
                '.prettierignore',
                '.prettierrc',
            ] as $file) {
                $fs->dumpFile(
                    $installDir.$file,
                    file_get_contents(__DIR__.'/../../../stubs/'.$file),
                );
            }

            ReplaceContentsInFile::run(
                'package.json',
                '"dev": "vite"',
                "\"dev\": \"vite\",\n       \"format\": \"npx prettier --write resources/\"",
            );

            Process::fromShellCommandline('npm run format')
                ->setWorkingDirectory($installDir)
                ->setTimeout(300)
                ->mustRun();
        } catch (Throwable $t) {
            return new PrettierDto(
                result: false,
                errors: [$t->getMessage()],
            );
        }

        return new PrettierDto(
            result: true,
        );
    }
}
