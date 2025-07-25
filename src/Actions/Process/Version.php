<?php

declare(strict_types=1);

namespace Laces\Actions\Process;

use Laces\Actions\Support\ReplaceContentsInFile;
use Laces\DataTransferObjects\Process\VersionDto;
use Symfony\Component\Filesystem\Filesystem;
use Throwable;

class Version
{
    /**
     * Update composer.json with new Laces versions.
     */
    public static function run(string $laravelVersion, string $livewireStarterKitVersion): VersionDto
    {
        $fs = new Filesystem;
        $installDir = __DIR__.'/../../../.working/install/';

        try {
            $fs->dumpFile(
                $installDir.'README.md',
                file_get_contents(__DIR__.'/../../../stubs/README.md'),
            );

            ReplaceContentsInFile::run(
                'composer.json',
                '"extra": {',
                "\"extra\": {\n        \"laces\": {\n            \"laravel-version\": \"$laravelVersion\",\n            \"livewire-starter-kit-version\": \"$livewireStarterKitVersion\"\n        },",
            );

            ReplaceContentsInFile::run(
                'composer.json',
                '"description": "The official Laravel starter kit for Livewire."',
                '"description": "The Laces starter kit - an opinionated set of improvements to the official Laravel starter kit for Livewire."',
            );

            ReplaceContentsInFile::run(
                'composer.json',
                '"name": "laravel/livewire-starter-kit"',
                '"name": "legecha/laces"',
            );
        } catch (Throwable $t) {
            return new VersionDto(
                result: false,
                errors: [$t->getMessage()],
            );
        }

        return new VersionDto(
            result: true,
        );
    }
}
