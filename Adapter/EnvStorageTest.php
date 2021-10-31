<?php

declare(strict_types=1);

namespace Lazier\Csv;

use Lazier\Storage\Adapter\EnvStorage;
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
        self::assertTrue($this->store->hasItem('LAZIER_ENV'));
        self::assertEquals('test', $this->store->getItem('LAZIER_ENV'));
    }

    public function testItHasMoreThanOneEnvironmentVariable(): void
    {
        self::assertGreaterThanOrEqual(1, count($this->store));
    }

    public function testItRemovesVariable(): void
    {
        $envCount = count($this->store);

        $this->store->removeItem('LAZIER_ENV');

        self::assertCount($envCount - 1, $this->store);
        self::assertFalse($this->store->hasItem('LAZIER_ENV'));
    }

    public function testItAddsVariable(): void
    {
        $this->store->setItem('LAZIER_TEST_NEW_VARIABLE', 'true');

        self::assertEquals('true', getenv('LAZIER_TEST_NEW_VARIABLE'));
    }
}
