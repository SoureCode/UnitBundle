<?php

namespace Model;

use BcMath\Number;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SoureCode\Bundle\Unit\Model\Distance;
use SoureCode\Bundle\Unit\Model\Length\Centimeter;
use SoureCode\Bundle\Unit\Model\Length\Decameter;
use SoureCode\Bundle\Unit\Model\Length\Decimeter;
use SoureCode\Bundle\Unit\Model\Length\Hectometer;
use SoureCode\Bundle\Unit\Model\Length\Kilometer;
use SoureCode\Bundle\Unit\Model\Length\LengthUnitInterface;
use SoureCode\Bundle\Unit\Model\Length\Meter;
use SoureCode\Bundle\Unit\Model\Length\Micrometer;
use SoureCode\Bundle\Unit\Model\Length\Millimeter;
use SoureCode\Bundle\Unit\Model\Length\Nanometer;
use SoureCode\Bundle\Unit\Model\Length\Picometer;

class DistanceTest extends TestCase
{
    public static function formatDataProvider(): array
    {
        return [
            [new Centimeter(5), '5cm'],
            [new Centimeter(55), '5.5dm'],
            [new Centimeter(555), '5.55m'],
            [new Centimeter(5555), '5.555dam'],

            [new Decameter(5), '5dam'],
            [new Decameter(55), '5.5hm'],
            [new Decameter(555), '5.55km'],
            [new Decameter(5555), '55.55km'],

            [new Decimeter(5), '5dm'],
            [new Decimeter(55), '5.5m'],
            [new Decimeter(555), '5.55dam'],
            [new Decimeter(5555), '5.555hm'],

            [new Hectometer(5), '5hm'],
            [new Hectometer(55), '5.5km'],
            [new Hectometer(555), '55.5km'],
            [new Hectometer(5555), '555.5km'],

            [new Kilometer(5), '5km'],
            [new Kilometer(55), '55km'],
            [new Kilometer(555), '555km'],
            [new Kilometer(5555), '5555km'],

            [new Meter(5), '5m'],
            [new Meter(55), '5.5dam'],
            [new Meter(555), '5.55hm'],
            [new Meter(5555), '5.555km'],

            [new Micrometer(5), '5μm'],
            [new Micrometer(55), '55μm'],
            [new Micrometer(555), '555μm'],
            [new Micrometer(5555), '5.555mm'],

            [new Millimeter(5), '5mm'],
            [new Millimeter(55), '5.5cm'],
            [new Millimeter(555), '5.55dm'],
            [new Millimeter(5555), '5.555m'],

            [new Nanometer(5), '5nm'],
            [new Nanometer(55), '55nm'],
            [new Nanometer(555), '555nm'],
            [new Nanometer(5555), '5.555μm'],

            [new Picometer(5), '5pm'],
            [new Picometer(55), '55pm'],
            [new Picometer(555), '555pm'],
            [new Picometer(5555), '5.555nm'],
        ];
    }

    public static function floorDataProvider(): array
    {
        return [
            [0, Meter::class, 0],
            [1.2, Meter::class, 1],
            [0.8, Meter::class, 0],

            [0, Kilometer::class, 0],
            [1200, Kilometer::class, 1000],
        ];
    }

    public static function ceilDataProvider(): array
    {
        return [
            [0, Meter::class, 0],
            [0.5, Meter::class, 1],
            [1.2, Meter::class, 2],

            [0, Kilometer::class, 0],
            [3599, Kilometer::class, 4000],
        ];
    }

    public static function roundDataProvider(): array
    {
        return [
            [0, Meter::class, 0],
            [0.2, Meter::class, 0],
            [0.5, Meter::class, 1],
            [0.8, Meter::class, 1],
            [1.2, Meter::class, 1],
            [1.5, Meter::class, 2],
            [1.8, Meter::class, 2],

            [0, Kilometer::class, 0],
            [1499, Kilometer::class, 1000],
            [1500, Kilometer::class, 2000],
        ];
    }

    public static function constructorDataProvider(): array
    {
        return [
            [60, 60],
            [3600.0, 3600],
            ['86400', 86400],
            [new Number(90000), 90000],
            [new Meter(1), 1],
            [new Kilometer(2.5), 2500],
        ];
    }

    public static function addDataProvider(): array
    {
        return [
            // Adding int
            [new Distance(60), 30, 90],
            [new Distance(100.5), 59.5, 160],
            [new Distance('120'), '30', 150],
            [new Distance(200), new Number(100), 300],
            [new Distance(0), new Meter(5), 5],
            [new Distance(5), new Meter(5), 10],
            [new Distance(3600), new Kilometer(30), 33600],
            [new Distance(400), new Distance(100), 500],
            [new Distance(0), new Distance(123456789), 123456789],
        ];
    }

    public static function subDataProvider(): array
    {
        return [
            // Subtract int
            [new Distance(60), 30, 30],
            [new Distance(100.5), 59.5, 41],
            [new Distance('120'), '30', 90],
            [new Distance(200), new Number(100), 100],
            [new Distance(3600), new Meter(1), 3599],
            [new Distance(3600), new Kilometer(30), -26400],
            [new Distance(400), new Distance(100), 300],
            [new Distance(123456789), new Distance(123456789), 0],
        ];
    }

    public function testZero(): void
    {
        // Act
        $sut = Distance::zero();

        // Assert
        $this->assertEquals(0, $sut->getValue()->getValue());
    }

    #[DataProvider('formatDataProvider')]
    public function testFormat(LengthUnitInterface $input, string $expected): void
    {
        // Arrange
        $sut = new Distance($input);

        // Act
        $actual = $sut->format();

        // Assert
        $this->assertEquals($expected, $actual, 'failed to assert format for ' . $input::class);
    }

    /**
     * @param class-string<LengthUnitInterface> $unitClass
     */
    #[DataProvider('floorDataProvider')]
    public function testFloor(int|float $input, string $unitClass, int $expected): void
    {
        // Arrange
        $sut = new Distance($input);

        // Act
        $actual = $sut->floor($unitClass);

        // Assert
        $this->assertEquals(
            $expected,
            $actual->getValue()->getValue(),
            "Failed asserting floor result for input $input with unit $unitClass"
        );
    }

    /**
     * @param class-string<LengthUnitInterface> $unitClass
     */
    #[DataProvider('ceilDataProvider')]
    public function testCeil(int|float $input, string $unitClass, int $expected): void
    {
        // Arrange
        $sut = new Distance($input);

        // Act
        $actual = $sut->ceil($unitClass);

        // Assert
        $this->assertEquals(
            $expected,
            $actual->getValue()->getValue(),
            "Failed asserting ceil result for input $input with unit $unitClass"
        );
    }

    /**
     * @param class-string<LengthUnitInterface> $unitClass
     */
    #[DataProvider('roundDataProvider')]
    public function testRound(int|float $input, string $unitClass, int $expected): void
    {
        // Arrange
        $sut = new Distance($input);

        // Act
        $actual = $sut->round($unitClass);

        // Assert
        $this->assertEquals(
            $expected,
            $actual->getValue()->getValue(),
            "Failed asserting round result for input $input with unit $unitClass"
        );
    }

    #[DataProvider('constructorDataProvider')]
    public function testConstruct(LengthUnitInterface|Number|string|float|int $input, int $expected): void
    {
        // Act
        $result = new Distance($input);

        // Assert
        $this->assertEquals($expected, $result->getValue()->getValue());
    }

    #[DataProvider('addDataProvider')]
    public function testAdd(Distance $base, Distance|\DateInterval|LengthUnitInterface|Number|string|float|int $add, int $expected): void
    {
        // Act
        $result = $base->add($add);

        // Assert
        $this->assertEquals($expected, $result->getValue()->getValue());
    }

    #[DataProvider('subDataProvider')]
    public function testSub(Distance $base, Distance|\DateInterval|LengthUnitInterface|Number|string|float|int $add, int $expected): void
    {
        // Act
        $result = $base->sub($add);

        // Assert
        $this->assertEquals($expected, $result->getValue()->getValue());
    }
}
