<?php

declare(strict_types=1);

namespace Laces\Enums;

enum Git: string
{
    case Init = 'init';
    case Add = 'add';
    case Commit = 'commit';
    case MaybeCommit = 'maybe-commit';
}
