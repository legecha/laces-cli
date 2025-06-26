<?php

declare(strict_types=1);

namespace Laces\Contracts;

/**
 * Interface for result objects that may represent an error.
 */
interface Error
{
    /**
     * Determines if the result is in an error state.
     */
    public function hasError(): bool;

    /**
     * Returns a descriptive error message, if applicable.
     */
    public function errorMessage(): ?string;
}
