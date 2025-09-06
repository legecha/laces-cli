<?php

declare(strict_types=1);

namespace Laces\DataTransferObjects\Process;

use Laces\Contracts\Error;

readonly class PostInstallDto implements Error
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
            ? 'Error setting up post-install script: '.implode(', ', $this->errors ?? [])
            : null;
    }
}
