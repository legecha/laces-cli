<?php

declare(strict_types=1);

namespace Laces\DataTransferObjects\Prepare;

use Laces\Contracts\Error;

readonly class InstallLaravelWithLivewireStarterKitDto implements Error
{
    public function __construct(
        public bool $result,
        public ?string $error,
    ) {}

    /**
     * {@inheritDoc}
     */
    public function hasError(): bool
    {
        return ! $this->result;
    }

    /**
     * {@inheritDoc}
     */
    public function errorMessage(): ?string
    {
        return $this->hasError() ? $this->error ?? 'There was an error with the installation' : null;
    }
}
