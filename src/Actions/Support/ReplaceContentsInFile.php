<?php

declare(strict_types=1);

namespace Laces\Actions\Support;

use RuntimeException;
use Symfony\Component\Filesystem\Filesystem;

class ReplaceContentsInFile
{
    /**
     * Replace contents in a file.
     */
    public static function run(string $path, string $search, string $replace): void
    {
        $fs = new Filesystem;
        $installDir = __DIR__.'/../../../.working/install/';

        $contents = file_get_contents($installDir.$path);
        if ($contents === false) {
            throw new RuntimeException("Unable to read file: $path");
        }

        $newContents = str_replace($search, $replace, $contents);

        $fs->dumpFile($installDir.$path, $newContents);
    }
}
