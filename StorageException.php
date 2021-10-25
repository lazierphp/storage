<?php

declare(strict_types=1);

namespace Lazy\Storage;

use Exception;

class StorageException extends Exception
{
    public static function create(string $message): self
    {
        return new self($message);
    }
}
