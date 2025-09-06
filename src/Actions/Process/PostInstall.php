<?php

declare(strict_types=1);

namespace Laces\Actions\Process;

use Laces\Actions\Support\ReplaceContentsInFile;
use Laces\DataTransferObjects\Process\PostInstallDto;
use Symfony\Component\Filesystem\Filesystem;
use Throwable;

class PostInstall
{
    /**
     * Setup actions to be performed after installation.
     */
    public static function run(): PostInstallDto
    {
        $fs = new Filesystem;
        $installDir = __DIR__.'/../../../.working/install/';

        try {
            $fs->dumpFile(
                $installDir.'post-install.php',
                file_get_contents(__DIR__.'/../../../stubs/post-install.php'),
            );

            ReplaceContentsInFile::run(
                'composer.json',
                '"@php artisan migrate --graceful --ansi"',
                "\"@php artisan migrate --graceful --ansi\",\n            \"@php post-install.php\""
            );
        } catch (Throwable $t) {
            return new PostInstallDto(
                result: false,
                errors: [$t->getMessage()],
            );
        }

        return new PostInstallDto(
            result: true,
        );
    }
}
