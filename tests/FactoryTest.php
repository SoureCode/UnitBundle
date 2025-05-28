<?php

use PHPUnit\Framework\TestCase;
use SoureCode\Bundle\Unit\Factory\LengthFactory;
use SoureCode\Bundle\Unit\Factory\TimeFactory;
use SoureCode\Bundle\Unit\Model\Length\Kilometer;
use SoureCode\Bundle\Unit\Model\Time\Hour;

class FactoryTest extends TestCase
{
    public function testLengthFactory(): void
    {
        $value = LengthFactory::create(10, Kilometer::class);

        $this->assertInstanceOf(Kilometer::class, $value);
        $this->assertEquals(10, $value->getValue());
    }

    public function testInvalidLengthFactory(): void
    {
        $this->expectException(InvalidArgumentException::class);

        LengthFactory::create(10, Hour::class);
    }

    public function testTimeFactory(): void
    {
        $value = TimeFactory::create(10, Hour::class);

        $this->assertInstanceOf(Hour::class, $value);
        $this->assertEquals(10, $value->getValue());
    }

    public function testInvalidTimeFactory(): void
    {
        $this->expectException(InvalidArgumentException::class);

        TimeFactory::create(10, Kilometer::class);
    }
}
