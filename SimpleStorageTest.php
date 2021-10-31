<?php

declare(strict_types=1);

namespace Lazier\Storage;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @covers SimpleStorage
 */
class SimpleStorageTest extends TestCase
{
    use ProphecyTrait;

    /** @var ObjectProphecy|StorageAdapter */
    private ObjectProphecy $adapter;
    private SimpleStorage $simpleStorage;

    public function setUp(): void
    {
        $this->adapter = $this->prophesize(StorageAdapter::class);
        $this->simpleStorage = SimpleStorage::createWith($this->adapter->reveal());
    }

    public function testItCallsClearMethod(): void
    {
        $this->adapter->clear()->shouldBeCalled();
        $this->simpleStorage->clear();
    }

    public function testItCallsCountMethod(): void
    {
        $this->adapter->count()->willReturn(42);

        self::assertEquals(42, $this->simpleStorage->count());
    }

    public function testItCallsGetItemMethod(): void
    {
        $this->adapter->getItem('foo')->willReturn('bar');

        self::assertEquals('bar', $this->simpleStorage->getItem('foo'));
    }

    public function testItCallsRemoveItemMethod(): void
    {
        $this->adapter->removeItem('foobar')->shouldBeCalled();

        $this->simpleStorage->removeItem('foobar');
    }

    public function testItCallsSetItemMethod(): void
    {
        $this->adapter->setItem('foo', 'baz')->shouldBeCalled();

        $this->simpleStorage->setItem('foo', 'baz');
    }
}
