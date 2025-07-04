<?php

declare(strict_types=1);

namespace Laces\Actions\Process;

use Laces\DataTransferObjects\Process\TestingDto;
use Symfony\Component\Filesystem\Filesystem;
use Throwable;

class Testing
{
    /**
     * Improve testing setup.
     */
    public static function run(): TestingDto
    {
        $fs = new Filesystem;
        $installDir = __DIR__.'/../../../.working/install/';

        try {
            // Remove unwanted files.
            $fs->remove([
                $installDir.'tests/TestCase.php',
                $installDir.'tests/Feature/ExampleTest.php',
                $installDir.'tests/Unit/ExampleTest.php',
            ]);

            // Improve Pest setup file.
            $fs->dumpFile(
                $installDir.'tests/Pest.php',
                file_get_contents(__DIR__.'/../../../stubs/Pest.php'),
            );

            // Setup extra files.
            foreach (['Expectations.php', 'Helpers.php'] as $file) {
                $fs->dumpFile(
                    $installDir."tests/$file",
                    "<?php\n\ndeclare(strict_types=1);\n\n",
                );
            }
        } catch (Throwable $t) {
            return new TestingDto(
                result: false,
                errors: [$t->getMessage()],
            );
        }

        return new TestingDto(
            result: true,
        );
    }
}
