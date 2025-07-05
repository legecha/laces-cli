<?php

declare(strict_types=1);

namespace Laces\Actions\Process;

use Laces\DataTransferObjects\Process\FluxDto;
use Symfony\Component\Process\Process;
use Throwable;

class Flux
{
    /**
     * Install Flux Pro.
     */
    public static function run(string $email, string $key): FluxDto
    {
        try {
            $installDir = __DIR__.'/../../../.working/install/';

            Process::fromShellCommandline('composer config repositories.flux-pro composer https://composer.fluxui.dev --quiet')
                ->setWorkingDirectory($installDir)
                ->setTimeout(300)
                ->mustRun();

            Process::fromShellCommandline("composer config http-basic.composer.fluxui.dev $email $key --quiet")
                ->setWorkingDirectory($installDir)
                ->setTimeout(300)
                ->mustRun();

            Process::fromShellCommandline('composer require livewire/flux-pro --quiet')
                ->setWorkingDirectory($installDir)
                ->setTimeout(300)
                ->mustRun();
        } catch (Throwable $t) {
            return new FluxDto(
                result: false,
                errors: [$t->getMessage()],
            );
        }

        return new FluxDto(
            result: true,
        );
    }
}
