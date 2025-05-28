<?php

namespace Model;

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
use SoureCode\Bundle\Unit\Model\Time\Week;
use SoureCode\Bundle\Unit\Model\Time\Year;
use SoureCode\Bundle\Unit\Model\UnitInterface;

class StaticValueTest extends TestCase
{
    public static function unitTypeDataProvider(): array
    {
        return [
            [Centimeter::class, 'centimeter'],
            [Decameter::class, 'decameter'],
            [Decimeter::class, 'decimeter'],
            [Hectometer::class, 'hectometer'],
            [Kilometer::class, 'kilometer'],
            [Meter::class, 'meter'],
            [Micrometer::class, 'micrometer'],
            [Millimeter::class, 'millimeter'],
            [Nanometer::class, 'nanometer'],
            [Picometer::class, 'picometer'],

            [Day::class, 'day'],
            [Hour::class, 'hour'],
            [Microsecond::class, 'microsecond'],
            [Millisecond::class, 'millisecond'],
            [Minute::class, 'minute'],
            [Month::class, 'month'],
            [Nanosecond::class, 'nanosecond'],
            [Picosecond::class, 'picosecond'],
            [Second::class, 'second'],
            [Week::class, 'week'],
            [Year::class, 'year'],
        ];
    }

    public static function symbolDataProvider(): array
    {
        return [
            [Centimeter::class, 'cm'],
            [Decameter::class, 'dam'],
            [Decimeter::class, 'dm'],
            [Hectometer::class, 'hm'],
            [Kilometer::class, 'km'],
            [Meter::class, 'm'],
            [Micrometer::class, 'μm'],
            [Millimeter::class, 'mm'],
            [Nanometer::class, 'nm'],
            [Picometer::class, 'pm'],

            [Day::class, 'd'],
            [Hour::class, 'h'],
            [Microsecond::class, 'µs'],
            [Millisecond::class, 'ms'],
            [Minute::class, 'min'],
            [Month::class, 'mo'],
            [Nanosecond::class, 'ns'],
            [Picosecond::class, 'ps'],
            [Second::class, 's'],
            [Week::class, 'wk'],
            [Year::class, 'yr'],
        ];
    }

    /**
     * @param class-string<UnitInterface> $className
     */
    #[DataProvider('symbolDataProvider')]
    public function testGetSymbol(string $className, string $expectedSymbol): void
    {
        $this->assertEquals($expectedSymbol, $className::getSymbol());
    }

    /**
     * @param class-string<UnitInterface> $className
     */
    #[DataProvider('unitTypeDataProvider')]
    public function testUnitType(string $className, string $expectedSymbol): void
    {
        $this->assertEquals($expectedSymbol, $className::getUnitType());
    }
}
