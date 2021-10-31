<?php

declare(strict_types=1);

namespace Lazier\Storage;

class SimpleStorage implements StorageAdapter
{
    private function __construct(
        private StorageAdapter $adapter,
    ) {
    }

    public static function createWith(StorageAdapter $adapter): self
    {
        return new self(
            adapter: $adapter,
        );
    }

    public function clear(): void
    {
        $this->adapter->clear();
    }

    public function count(): int
    {
        return $this->adapter->count();
    }

    public function getItem(string $key): string
    {
        return $this->adapter->getItem($key);
    }

    public function removeItem(string $key): void
    {
        $this->adapter->removeItem($key);
    }

    public function setItem(string $key, string $value): void
    {
        $this->adapter->setItem($key, $value);
    }
}
