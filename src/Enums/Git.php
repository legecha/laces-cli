<?php

declare(strict_types=1);

namespace Laces\Enums;

enum Git: string
{
    case Add = 'add';
    case AddRemote = 'add-remote';
    case Commit = 'commit';
    case ForcePush = 'force-push';
    case Init = 'init';
    case MaybeCommit = 'maybe-commit';
}
