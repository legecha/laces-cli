<?php

declare(strict_types=1);

namespace Laces\Actions;

use Laces\DataTransferObjects\InstallLaravelWithLivewireStarterKitDto;
use Symfony\Component\Process\Process;
use Throwable;

class InstallLaravelWithLivewireStarterKit
{
    /**
     * Install Laravel with the Livewire Starter Kit into the temporary working folder.
     */
    public static function run(): InstallLaravelWithLivewireStarterKitDto
    {
        try {
            $cwd = realpath(__DIR__.'/../../');
            $result = true;
            $error = null;

            Process::fromShellCommandline('laravel new .working/install --livewire --livewire-class-components --pest --quiet')
                ->setWorkingDirectory($cwd)
                ->setTimeout(300)
                ->mustRun();
        } catch (Throwable $t) {
            $result = false;
            $error = $t->getMessage();
        }

        return new InstallLaravelWithLivewireStarterKitDto(
            result: $result,
            error: $error,
        );
    }
}
