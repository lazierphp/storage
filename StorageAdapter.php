<?php

declare(strict_types=1);

namespace Lazier\Storage;

use Countable;

interface StorageAdapter extends Countable
{
    public function clear(): void;
    public function getItem(string $key): string;
    public function removeItem(string $key): void;
    public function setItem(string $key, string $value): void;
}
