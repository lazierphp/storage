<?php

declare(strict_types=1);

namespace Lazy\Csv;

use Exception;
use Lazy\Storage\Adapter\JsonFileStorage;
use Lazy\Storage\KeyNotFound;
use PHPUnit\Framework\TestCase;

use function copy;
use function file_exists;
use function sys_get_temp_dir;
use function tempnam;
use function unlink;

use const DIRECTORY_SEPARATOR as DS;

/**
 * @covers JsonFileStorage
 */
class JsonFileStorageTest extends TestCase
{
    private string $existingTempFilename;
    private string $emptyTempFilename;

    public function setUp(): void
    {
        $existingTempFilename = tempnam(sys_get_temp_dir(), 'lazy_json_test_copy');
        $emptyTempFilename = tempnam(sys_get_temp_dir(), 'lazy_json_test_empty');

        if (!$existingTempFilename || !$emptyTempFilename) {
            throw new Exception('Cannot create temp files for unit test.');
        }

        $this->existingTempFilename = $existingTempFilename;
        $this->emptyTempFilename = $emptyTempFilename;
        unlink($this->emptyTempFilename);

        copy(__DIR__ . DS . 'JsonFileStorageTest.json', $this->existingTempFilename);
    }

    public function tearDown(): void
    {
        if (file_exists($this->existingTempFilename)) {
            unlink($this->existingTempFilename);
        }

        if (file_exists($this->emptyTempFilename)) {
            unlink($this->emptyTempFilename);
        }
    }

    public function testItCanReadFromTestFile(): void
    {
        $store = JsonFileStorage::create($this->existingTempFilename);

        self::assertEquals('bar', $store->getItem('foo'));
    }

    public function testItHasMoreThanOneItem(): void
    {
        $store = JsonFileStorage::create($this->existingTempFilename);

        self::assertGreaterThan(0, $store->count());
    }

    public function testItHasNoItemsIfFileNotExists(): void
    {
        $store = JsonFileStorage::create($this->emptyTempFilename);

        self::assertEquals(0, $store->count());
    }

    public function testItThrowsExceptionByAccessingInvalidKey(): void
    {
        $store = JsonFileStorage::create($this->emptyTempFilename);

        $this->expectException(KeyNotFound::class);
        $store->getItem('test');
    }

    public function testItSavesAutomatically(): void
    {
        $store = JsonFileStorage::create($this->emptyTempFilename);
        $store->setItem('foo', 'bar');

        self::assertFileExists($this->emptyTempFilename);
        $anotherStore = JsonFileStorage::create($this->emptyTempFilename);

        self::assertEquals('bar', $anotherStore->getItem('foo'));
    }

    public function testItHandlesOnlyStrings(): void
    {
        $store = JsonFileStorage::create($this->existingTempFilename);

        self::assertEquals('42', $store->getItem('test_int'));
        self::assertEquals('13.37', $store->getItem('test_float'));
        self::assertEquals('', $store->getItem('test_null'));
    }

    public function testItCanRemoveKeys(): void
    {
        $store = JsonFileStorage::create($this->existingTempFilename);

        self::assertTrue($store->hasItem('foo'));
        $store->removeItem('foo');

        self::assertFileExists($this->existingTempFilename);
        $anotherStore = JsonFileStorage::create($this->existingTempFilename);

        self::assertFalse($anotherStore->hasItem('foo'));
    }
}
