<?php

declare(strict_types=1);

namespace Laces\DataTransferObjects;

use Laces\Contracts\Error;

readonly class GetLatestLacesVersionsDto implements Error
{
    public function __construct(
        public ?string $laravelVersion = null,
        public ?string $livewireStarterKitVersion = null,
        private ?string $error = null
    ) {}

    /**
     * {@inheritDoc}
     */
    public function hasError(): bool
    {
        return $this->error !== null || $this->laravelVersion === null || $this->livewireStarterKitVersion === null;
    }

    /**
     * {@inheritDoc}
     */
    public function errorMessage(): ?string
    {
        return $this->error ?? 'Version could not be found.';
    }
}
