<?php

declare(strict_types=1);

namespace Laces\DataTransferObjects\Prepare;

use Laces\Contracts\Error;

readonly class GetLatestLaravelVersionDto implements Error
{
    public function __construct(
        public ?string $version = null,
        private ?string $error = null
    ) {}

    /**
     * {@inheritDoc}
     */
    public function hasError(): bool
    {
        return $this->error !== null || $this->version === null;
    }

    /**
     * {@inheritDoc}
     */
    public function errorMessage(): ?string
    {
        return $this->error ?? 'Version could not be found.';
    }
}
