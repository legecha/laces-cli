<?php

declare(strict_types=1);

namespace Laces\DataTransferObjects\Prepare;

use Laces\Contracts\Error;

readonly class CheckDependenciesDto implements Error
{
    public function __construct(
        public bool $result,
        /**
         * @var array<int, string>
         */
        public ?array $missing = null
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
        return $this->hasError()
            ? 'Missing required dependencies: '.implode(', ', $this->missing ?? [])
            : null;
    }
}
