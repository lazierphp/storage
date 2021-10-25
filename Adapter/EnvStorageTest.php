<?php

declare(strict_types=1);

namespace Lazy\Csv;

use Lazy\Storage\Adapter\EnvStorage;
use PHPUnit\Framework\TestCase;

use function count;
use function getenv;

/**
 * @covers EnvStorage
 */
class EnvStorageTest extends TestCase
{
    private EnvStorage $store;

    public function setUp(): void
    {
        $this->store = EnvStorage::create();
    }

    public function testItHasTestEnvironment(): void
    {
        self::assertTrue($this->store->hasItem('LAZY_ENV'));
        self::assertEquals('test', $this->store->getItem('LAZY_ENV'));
    }

    public function testItHasMoreThanOneEnvironmentVariable(): void
    {
        self::assertGreaterThanOrEqual(1, count($this->store));
    }

    public function testItRemovesVariable(): void
    {
        $envCount = count($this->store);

        $this->store->removeItem('LAZY_ENV');

        self::assertCount($envCount - 1, $this->store);
        self::assertFalse($this->store->hasItem('LAZY_ENV'));
    }

    public function testItAddsVariable(): void
    {
        $this->store->setItem('LAZY_TEST_NEW_VARIABLE', 'true');

        self::assertEquals('true', getenv('LAZY_TEST_NEW_VARIABLE'));
    }
}
