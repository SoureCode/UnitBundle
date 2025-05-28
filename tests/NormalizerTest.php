<?php

namespace SoureCode\Bundle\Unit\Tests;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SoureCode\Bundle\Unit\Model\Length\Centimeter;
use SoureCode\Bundle\Unit\Model\Length\Decameter;
use SoureCode\Bundle\Unit\Model\Length\Decimeter;
use SoureCode\Bundle\Unit\Model\Length\Hectometer;
use SoureCode\Bundle\Unit\Model\Length\Kilometer;
use SoureCode\Bundle\Unit\Model\Length\Meter;
use SoureCode\Bundle\Unit\Model\Length\Micrometer;
use SoureCode\Bundle\Unit\Model\Length\Millimeter;
use SoureCode\Bundle\Unit\Model\Length\Nanometer;
use SoureCode\Bundle\Unit\Model\Length\Picometer;
use SoureCode\Bundle\Unit\Model\Time\Day;
use SoureCode\Bundle\Unit\Model\Time\Hour;
use SoureCode\Bundle\Unit\Model\Time\Microsecond;
use SoureCode\Bundle\Unit\Model\Time\Millisecond;
use SoureCode\Bundle\Unit\Model\Time\Minute;
use SoureCode\Bundle\Unit\Model\Time\Month;
use SoureCode\Bundle\Unit\Model\Time\Nanosecond;
use SoureCode\Bundle\Unit\Model\Time\Picosecond;
use SoureCode\Bundle\Unit\Model\Time\Second;
use SoureCode\Bundle\Unit\Model\Time\Year;
use SoureCode\Bundle\Unit\Normalizer\LengthNormalizer;
use SoureCode\Bundle\Unit\Normalizer\TimeNormalizer;

class NormalizerTest extends TestCase
{
    /**
     * @return array<array<int|float, string>>
     */
    public static function lengthDataProvider(): array
    {
        return [
            [0.000000000000005, Picometer::class],
            [0.00000000000005, Picometer::class],
            [0.0000000000005, Picometer::class],
            [0.000000000005, Picometer::class],
            [0.00000000005, Picometer::class],
            [0.0000000005, Picometer::class],
            [0.000000005, Nanometer::class],
            [0.00000005, Nanometer::class],
            [0.0000005, Nanometer::class],
            [0.000005, Micrometer::class],
            [0.00005, Micrometer::class],
            [0.0005, Micrometer::class],
            [0.005, Millimeter::class],
            [0.05, Centimeter::class],
            [0.5, Decimeter::class],
            [0, Meter::class],
            [5, Meter::class],
            [55, Decameter::class],
            [555, Hectometer::class],
            [5555, Kilometer::class],
            [55555, Kilometer::class],
            [555555, Kilometer::class],
            [5555555, Kilometer::class],
            [55555555, Kilometer::class],
            [555555555, Kilometer::class],
            [5555555555, Kilometer::class],
            [55555555555, Kilometer::class],
            [555555555555, Kilometer::class],
            [5555555555555, Kilometer::class],
            [55555555555555, Kilometer::class],
        ];
    }

    /**
     * @param class-string $expectedClassName
     */
    #[DataProvider('lengthDataProvider')]
    public function testLengthNormalizer(int|float $value, string $expectedClassName): void
    {
        $length = new Meter($value);

        $actual = LengthNormalizer::normalize($length);

        $this->assertInstanceOf($expectedClassName, $actual);
    }

    /**
     * @return array<array<int|float, string>>
     */
    public static function timeDataProvider(): array
    {
        return [
            [0.000000000000005, Picosecond::class],
            [0.00000000000005, Picosecond::class],
            [0.0000000000005, Picosecond::class],
            [0.000000000005, Picosecond::class],
            [0.00000000005, Picosecond::class],
            [0.0000000005, Picosecond::class],
            [0.000000005, Nanosecond::class],
            [0.00000005, Nanosecond::class],
            [0.0000005, Nanosecond::class],
            [0.000005, Microsecond::class],
            [0.00005, Microsecond::class],
            [0.0005, Microsecond::class],
            [0.005, Millisecond::class],
            [0.05, Millisecond::class],
            [0.5, Millisecond::class],
            [0, Second::class],
            [5, Second::class],
            [55, Second::class],
            [555, Minute::class],
            [5555, Hour::class],
            [55555, Hour::class],
            [555555, Day::class],
            [5555555, Month::class],
            [55555555, Year::class],
            [555555555, Year::class],
            [5555555555, Year::class],
            [55555555555, Year::class],
            [555555555555, Year::class],
            [5555555555555, Year::class],
            [55555555555555, Year::class],
        ];
    }

    /**
     * @param class-string $expectedClassName
     */
    #[DataProvider('timeDataProvider')]
    public function testTimeNormalizer(int|float $value, string $expectedClassName): void
    {
        $time = new Second($value);

        $actual = TimeNormalizer::normalize($time);

        $this->assertInstanceOf($expectedClassName, $actual);
    }
}
