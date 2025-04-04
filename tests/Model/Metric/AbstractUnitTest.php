<?php

namespace SoureCode\Bundle\Unit\Tests\Model\Metric;

use BcMath\Number;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RoundingMode;
use SoureCode\Bundle\Unit\Model\Metric\AbstractUnit;
use SoureCode\Bundle\Unit\Model\Metric\Length\Meter;

class AbstractUnitTest extends TestCase
{
    #[DataProvider('constructDataProvider')]
    public function testConstructor(Number|string|int|float $value, Number $expected, string $message): void
    {
        // Act
        $meter = new Meter($value);

        // Assert
        self::assertEquals($expected, $meter->getValue(), sprintf('Failed asserting for %s', $message));
    }

    public static function constructDataProvider(): array
    {
        return [
            [new Number('123'), new Number('123'), 'number object'],
            [123, new Number('123'), 'int'],
            [123.45, new Number('123.45'), 'float'],
            ['123', new Number('123'), 'string integer'],
            ['123.45', new Number('123.45'), 'string float'],
            ['1.23e3', new Number('1230'), 'string scientific lowercase'],
            ['1.23E3', new Number('1230'), 'string scientific uppercase'],
            [-123, new Number('-123'), 'negative int'],
            [-123.45, new Number('-123.45'), 'negative float'],
            ['-1.23e3', new Number('-1230'), 'negative scientific'],
            [0, new Number('0'), 'zero int'],
            [0.0, new Number('0'), 'zero float'],
            ['0', new Number('0'), 'string zero'],
            ['0.0', new Number('0'), 'string zero float'],
            [PHP_INT_MAX, new Number((string)PHP_INT_MAX), 'large int'],
            [PHP_INT_MIN, new Number((string)PHP_INT_MIN), 'small int'],
            [1.79e308, new Number('179000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000'), 'large float'],
            [2.22e-308, new Number('0.0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000222'), 'small float'],
            ['1.79e308', new Number('179000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000'), 'string large float'],
            ['2.22e-308', new Number('0.0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000222'), 'string small float'],
            ['1e3', new Number('1000'), 'scientific no decimal'],
            ['-1e3', new Number('-1000'), 'negative scientific no decimal'],
            ['+123', new Number('123'), 'positive sign int'],
            ['+123.45', new Number('123.45'), 'positive sign float'],
            ['+1.23e3', new Number('1230'), 'positive sign scientific'],
            ['000123', new Number('123'), 'leading zeros int'],
            ['000123.450', new Number('123.45'), 'leading zeros float'],
            ['123.45000', new Number('123.45'), 'trailing zeros float'],
            ['.5', new Number('0.5'), 'only decimal point'],
            ['.5e1', new Number('5'), 'leading dot scientific'],
        ];
    }

    public function testConstructorInvalid(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new Meter('invalid');
    }

    public function testRoundCreatesNewInstanceWithRoundedValue(): void
    {
        // Arrange
        $sut = new Meter(3.14);

        // Act
        $result = $sut->round(0, RoundingMode::PositiveInfinity);

        // Assert
        $this->assertInstanceOf(Meter::class, $result);
        $this->assertNotSame($sut, $result);
        $this->assertEquals(new Meter(4), $result);
    }

    public function testFormatWithPrecisionAndMode(): void
    {
        // Arrange
        $meter = new Meter(123.456);

        // Act
        $result = $meter->format(2, RoundingMode::HalfEven);

        // Assert
        $this->assertSame('123.46m', $result);
    }

    public function testFormatWithDefaultPrecision(): void
    {
        // Arrange
        Meter::$defaultPrecision = 1;
        Meter::$defaultRoundingMode = RoundingMode::TowardsZero;

        $meter = new Meter(123.456);

        // Act
        $result = $meter->format();

        // Assert
        $this->assertSame('123.4m', $result);

        // Reset
        Meter::$defaultPrecision = null;
        Meter::$defaultRoundingMode = \RoundingMode::HalfAwayFromZero;
    }

    public function testFormatWithoutPrecisionUsesRawValue(): void
    {
        // Arrange
        Meter::$defaultPrecision = null; // means no rounding will be applied
        Meter::$defaultRoundingMode = RoundingMode::TowardsZero;

        $meter = new Meter(123.456);

        // Act
        $result = $meter->format();

        // Assert
        $this->assertSame('123.456m', $result);

        // Reset
        Meter::$defaultPrecision = null;
        Meter::$defaultRoundingMode = \RoundingMode::HalfAwayFromZero;
    }

    #[DataProvider('notationDataProvider')]
    public function testExpandScientificNotation(string $input, string $expected): void
    {
        // Act
        $result = AbstractUnit::expandScientificNotation($input); // Replace SomeClass with your actual class name

        // Assert
        $this->assertSame($expected, $result);
    }

    public static function notationDataProvider(): array
    {
        return [
            ['123.45', '123.45'],
            ['0.00123', '0.00123'],
            ['1e3', '1000'],
            ['7e2', '700'],
            ['1.23e3', '1230'],
            ['5.6e1', '56'],
            ['9.99e4', '99900'],
            ['1e-3', '0.001'],
            ['7e-2', '0.07'],
            ['1.23e-2', '0.0123'],
            ['5.6e-1', '0.56'],
            ['9.99e-4', '0.000999'],
            ['0.1e2', '10'],
            ['0.01e3', '10'],
            ['1.2E3', '1200'],
            ['1.23e0', '1.23'],
            ['0e3', '0'],
        ];
    }

    public function testToStringUsesDefaultPrecisionAndRoundingMode(): void
    {
        // Arrange
        Meter::$defaultPrecision = 2;
        Meter::$defaultRoundingMode = RoundingMode::HalfEven;

        $meter = new Meter(123.456);

        // Act
        $result = (string)$meter;

        // Assert
        $this->assertSame('123.46m', $result);

        // Reset
        Meter::$defaultPrecision = null;
        Meter::$defaultRoundingMode = \RoundingMode::HalfAwayFromZero;
    }
}
