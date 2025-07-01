<?php

declare(strict_types=1);

namespace Laces\Actions\Prepare;

use Laces\DataTransferObjects\Prepare\SetupWorkingFolderDto;
use Symfony\Component\Filesystem\Filesystem;
use Throwable;

class SetupWorkingFolder
{
    /**
     * Setup the temporary working folder.
     */
    public static function run(): SetupWorkingFolderDto
    {
        try {
            $fs = new Filesystem;
            $path = __DIR__.'/../../../.working';
            $result = true;
            $messages = [];

            if ($fs->exists($path)) {
                $messages[] = 'Folder already exists. Removing...';
                $fs->remove($path);
            }

            $messages[] = 'Creating working folder...';
            $fs->mkdir($path, 0755);

            if (! is_writable($path)) {
                $result = false;
                $messages[] = 'Folder is not writeable.';
            } else {
                $path = realpath($path);
            }

            $messages = array_merge(["Setting up working folder: <comment>$path</>"], $messages);
            $messages[] = 'Folder created successfully.';
        } catch (Throwable $t) {
            $result = false;
            $messages[] = "Error setting up working folder: {$t->getMessage()}";
        }

        return new SetupWorkingFolderDto(
            result: $result,
            messages: $messages,
        );
    }
}
