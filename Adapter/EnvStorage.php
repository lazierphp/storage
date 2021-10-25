<?php

declare(strict_types=1);

namespace Lazy\Storage\Adapter;

use Lazy\Storage\KeyNotFound;
use Lazy\Storage\StorageAdapter;

use function count;
use function getenv;
use function is_string;
use function putenv;
use function sprintf;

class EnvStorage implements StorageAdapter
{
    private function __construct()
    {
    }

    public static function create(): self
    {
        return new self();
    }

    public function clear(): void
    {
    }

    public function count(): int
    {
        return count(getenv());
    }

    public function getItem(string $key): string
    {
        $value = getenv($key);

        if ($value === false) {
            throw KeyNotFound::createByKey($key);
        }

        return $value;
    }

    public function hasItem(string $key): bool
    {
        return is_string(getenv($key));
    }

    public function removeItem(string $key): void
    {
        putenv(sprintf('%s', $key));
    }

    public function setItem(string $key, string $value): void
    {
        putenv(sprintf('%s=%s', $key, $value));
    }
}
