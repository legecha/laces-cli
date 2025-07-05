<?php

declare(strict_types=1);

namespace Laces\Actions\Process;

use Laces\DataTransferObjects\Process\ViewsDto;
use Symfony\Component\Filesystem\Filesystem;
use Throwable;

class Views
{
    /**
     * Improve default views.
     */
    public static function run(): ViewsDto
    {
        $fs = new Filesystem;
        $installDir = __DIR__.'/../../../.working/install/';

        foreach ([
            'sidebar.blade.php' => 'resources/views/components/layouts/app/sidebar.blade.php',
            'welcome.blade.php' => 'resources/views/welcome.blade.php',
        ] as $stub => $file) {
            try {
                $fs->dumpFile(
                    $installDir.$file,
                    file_get_contents(__DIR__.'/../../../stubs/'.$stub),
                );
            } catch (Throwable $t) {
                return new ViewsDto(
                    result: false,
                    errors: [$t->getMessage()],
                );
            }
        }

        return new ViewsDto(
            result: true,
        );
    }
}
