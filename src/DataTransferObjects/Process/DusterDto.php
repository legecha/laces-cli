<?php

declare(strict_types=1);

namespace Laces\DataTransferObjects\Process;

use Laces\Contracts\Error;

readonly class DusterDto implements Error
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
            ? 'Error installing Duster: '.implode(', ', $this->errors ?? [])
            : null;
    }
}
