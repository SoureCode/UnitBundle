<?php

namespace SoureCode\Bundle\Unit\Tests\Model\Metric\Conversion;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SoureCode\Bundle\Unit\Model\Metric\Converter\LengthConverter;
use SoureCode\Bundle\Unit\Model\Metric\Length\Centimeter;
use SoureCode\Bundle\Unit\Model\Metric\Length\Decameter;
use SoureCode\Bundle\Unit\Model\Metric\Length\Decimeter;
use SoureCode\Bundle\Unit\Model\Metric\Length\Hectometer;
use SoureCode\Bundle\Unit\Model\Metric\Length\Kilometer;
use SoureCode\Bundle\Unit\Model\Metric\Length\LengthUnitInterface;
use SoureCode\Bundle\Unit\Model\Metric\Length\Meter;
use SoureCode\Bundle\Unit\Model\Metric\Length\Micrometer;
use SoureCode\Bundle\Unit\Model\Metric\Length\Millimeter;
use SoureCode\Bundle\Unit\Model\Metric\Length\Nanometer;
use SoureCode\Bundle\Unit\Model\Metric\Length\Picometer;
use SoureCode\Bundle\Unit\Model\Metric\Prefix;
use SoureCode\Bundle\Unit\Tests\AbstractKernelTestCase;

final class LengthConverterTest extends AbstractKernelTestCase
{
    #[DataProvider('lengthUnitDataProvider')]
    public function testCrossConvert(LengthUnitInterface $unit, array $expected): void
    {
        $container = self::getContainer();
        $converter = $container->get('soure_code.unit.length.converter');

        self::assertEquals($expected[0] . Kilometer::getSymbol(), $converter->convert($unit, Prefix::KILO)->format(), 'convert to kilometer failed');
        self::assertEquals($expected[1] . Hectometer::getSymbol(), $converter->convert($unit, Prefix::HECTO)->format(), 'convert to hectometer failed');
        self::assertEquals($expected[2] . Decameter::getSymbol(), $converter->convert($unit, Prefix::DECA)->format(), 'convert to decameter failed');
        self::assertEquals($expected[3] . Meter::getSymbol(), $converter->convert($unit, Prefix::BASE)->format(), 'convert to centimeter failed');
        self::assertEquals($expected[4] . Decimeter::getSymbol(), $converter->convert($unit, Prefix::DECI)->format(), 'convert to centimeter failed');
        self::assertEquals($expected[5] . Centimeter::getSymbol(), $converter->convert($unit, Prefix::CENTI)->format(), 'convert to centimeter failed');
        self::assertEquals($expected[6] . Millimeter::getSymbol(), $converter->convert($unit, Prefix::MILLI)->format(), 'convert to millimeter failed');
        self::assertEquals($expected[7] . Micrometer::getSymbol(), $converter->convert($unit, Prefix::MICRO)->format(), 'convert to micrometer failed');
        self::assertEquals($expected[8] . Nanometer::getSymbol(), $converter->convert($unit, Prefix::NANO)->format(), 'convert to nanometer failed');
        self::assertEquals($expected[9] . Picometer::getSymbol(), $converter->convert($unit, Prefix::PICO)->format(), 'convert to picometer failed');
    }

    public static function lengthUnitDataProvider(): array
    {
        return [
            [new Kilometer(3.14), ['3.14', '31.4', '314', '3140', '31400', '314000', '3140000', '3140000000', '3140000000000', '3140000000000000']],
            [new Hectometer(3.14), ['0.314', '3.14', '31.4', '314', '3140', '31400', '314000', '314000000', '314000000000', '314000000000000']],
            [new Decameter(3.14), ['0.0314', '0.314', '3.14', '31.4', '314', '3140', '31400', '31400000', '31400000000', '31400000000000']],
            [new Meter(3.14), ['0.00314', '0.0314', '0.314', '3.14', '31.4', '314', '3140', '3140000', '3140000000', '3140000000000']],
            [new Decimeter(3.14), ['0.000314', '0.00314', '0.0314', '0.314', '3.14', '31.4', '314', '314000', '314000000', '314000000000']],
            [new Centimeter(3.14), ['0.0000314', '0.000314', '0.00314', '0.0314', '0.314', '3.14', '31.4', '31400', '31400000', '31400000000']],
            [new Millimeter(3.14), ['0.00000314', '0.0000314', '0.000314', '0.00314', '0.0314', '0.314', '3.14', '3140', '3140000', '3140000000']],
            [new Micrometer(3.14), ['0.00000000314', '0.0000000314', '0.000000314', '0.00000314', '0.0000314', '0.000314', '0.00314', '3.14', '3140', '3140000']],
            [new Nanometer(3.14), ['0.00000000000314', '0.0000000000314', '0.000000000314', '0.00000000314', '0.0000000314', '0.000000314', '0.00000314', '0.00314', '3.14', '3140']],
            [new Picometer(3.14), ['0.00000000000000314', '0.0000000000000314', '0.000000000000314', '0.00000000000314', '0.0000000000314', '0.000000000314', '0.00000000314', '0.00000314', '0.00314', '3.14']],
        ];
    }

    public function testConvertSamePrefix(): void
    {
        // Arrange
        $container = self::getContainer();
        $converter = $container->get('soure_code.unit.length.converter');
        $base = new Meter("3.14");

        // Act
        $result = $converter->convert($base, Prefix::BASE);

        // Assert
        self::assertSame($base, $result, 'convert to same prefix failed');
    }

    public function testConversionIntoInvalidPrefix(): void
    {
        // Arrange
        $container = self::getContainer();
        $converter = $container->get('soure_code.unit.length.converter');
        $base = new Meter("3.14");

        // Act & Assert
        $this->expectException(\InvalidArgumentException::class);

        $result = $converter->convert($base, Prefix::GIGA);
    }
}
