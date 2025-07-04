<?php

declare(strict_types=1);

namespace Laces\Actions\Process;

use Laces\Actions\Support\ReplaceContentsInFile;
use Laces\DataTransferObjects\Process\ConfigDto;
use Throwable;

class Config
{
    /**
     * Setup config.
     */
    public static function run(): ConfigDto
    {
        try {
            // Setup .env file.
            ReplaceContentsInFile::run(
                '.env',
                'APP_FAKER_LOCALE=en_US',
                "APP_FAKER_LOCALE=en_GB\nAPP_TIMEZONE=\"Europe/London\"",
            );

            // Setup config/app.php.
            ReplaceContentsInFile::run(
                'config/app.php',
                "'timezone' => 'UTC',",
                "'timezone' => env('APP_TIMEZONE', 'UTC'),",
            );
        } catch (Throwable $t) {
            return new ConfigDto(
                result: false,
                errors: [$t->getMessage()],
            );
        }

        return new ConfigDto(
            result: true,
        );
    }
}
