<?php

declare(strict_types=1);

namespace Lazy\Storage;

use function sprintf;

class KeyNotFound extends StorageException
{
    public static function createByKey(string $key): self
    {
        return new self(sprintf('Key "%s" does not exist.', $key));
    }
}
