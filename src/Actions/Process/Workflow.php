<?php

declare(strict_types=1);

namespace Laces\Actions\Process;

use Laces\DataTransferObjects\Process\WorkflowDto;
use Symfony\Component\Filesystem\Filesystem;
use Throwable;

class Workflow
{
    /**
     * Remove GitHub workflows.
     */
    public static function run(): WorkflowDto
    {
        $fs = new Filesystem;
        $installDir = __DIR__.'/../../../.working/install/';

        try {
            // Remove unwanted files.
            $fs->remove($installDir.'.github');
        } catch (Throwable $t) {
            return new WorkflowDto(
                result: false,
                errors: [$t->getMessage()],
            );
        }

        return new WorkflowDto(
            result: true,
        );
    }
}
