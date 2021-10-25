<?php

declare(strict_types=1);

namespace Lazy\Storage\Adapter;

use Lazy\Storage\KeyNotFound;
use Lazy\Storage\StorageAdapter;

use function array_key_exists;
use function array_map;
use function count;
use function file_exists;
use function file_get_contents;
use function file_put_contents;
use function json_decode;
use function json_encode;
use function touch;

use const JSON_THROW_ON_ERROR;

class JsonFileStorage implements StorageAdapter
{
    /**
     * @param array<string, string> $data
     */
    private function __construct(
        private string $filename,
        private array $data = [],
    ) {
    }

    public static function create(string $filename): self
    {
        $data = [];

        if (file_exists($filename)) {
            $data = json_decode(
                (string) file_get_contents($filename),
                associative: true,
                flags: JSON_THROW_ON_ERROR,
            ) ?? [];

            $data = array_map(static function ($value) {
                return (string) $value;
            }, $data);
        }

        return new self(
            filename: $filename,
            data: $data,
        );
    }

    public function clear(): void
    {
        $this->data = [];
        $this->save();
    }

    public function count(): int
    {
        return count($this->data);
    }

    public function getItem(string $key): string
    {
        if (!$this->hasItem($key)) {
            throw KeyNotFound::createByKey($key);
        }

        return $this->data[$key];
    }

    public function hasItem(string $key): bool
    {
        return array_key_exists($key, $this->data);
    }

    public function removeItem(string $key): void
    {
        unset($this->data[$key]);
        $this->save();
    }

    public function setItem(string $key, string $value): void
    {
        $this->data[$key] = $value;
        $this->save();
    }

    private function save(): void
    {
        if (!file_exists($this->filename)) {
            touch($this->filename);
        }

        file_put_contents(
            $this->filename,
            json_encode($this->data, flags: JSON_THROW_ON_ERROR & JSON_PRETTY_PRINT),
        );
    }
}
