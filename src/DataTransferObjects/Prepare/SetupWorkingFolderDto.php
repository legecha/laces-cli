<?php

declare(strict_types=1);

namespace Laces\DataTransferObjects\Prepare;

use Laces\Contracts\Error;

readonly class SetupWorkingFolderDto implements Error
{
    public function __construct(
        public bool $result,
        /**
         * @var array<int, string>
         */
        public ?array $messages = null
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
            ? implode("\n", $this->messages)
            : null;
    }
}
