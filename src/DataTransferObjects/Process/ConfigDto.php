<?php

declare(strict_types=1);

namespace Laces\DataTransferObjects\Process;

use Laces\Contracts\Error;

readonly class ConfigDto implements Error
{
    public function __construct(
        public bool $result,
        /**
         * @var array<int, string>
         */
        public ?array $errors = null
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
            ? 'Failed to setup config: '.implode(', ', $this->errors ?? [])
            : null;
    }
}
