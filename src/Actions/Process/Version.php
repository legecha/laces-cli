<?php

declare(strict_types=1);

namespace Laces\Actions\Process;

use Laces\Actions\Support\ReplaceContentsInFile;
use Laces\DataTransferObjects\Process\VersionDto;
use Throwable;

class Version
{
    /**
     * Update composer.json with new Laces versions.
     */
    public static function run(string $laravelVersion, string $livewireStarterKitVersion): VersionDto
    {
        try {
            ReplaceContentsInFile::run(
                'composer.json',
                '"extra": {',
                "\"extra\": {\n        \"laces\": {\n            \"laravel-version\": \"$laravelVersion\",\n            \"livewire-starter-kit-version\": \"$livewireStarterKitVersion\"\n        },",
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
