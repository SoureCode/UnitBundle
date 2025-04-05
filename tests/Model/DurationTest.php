<?php

namespace Model;

use BcMath\Number;
use DateInterval;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SoureCode\Bundle\Unit\Model\Duration;
use SoureCode\Bundle\Unit\Model\Time\Day;
use SoureCode\Bundle\Unit\Model\Time\Hour;
use SoureCode\Bundle\Unit\Model\Time\Minute;
use SoureCode\Bundle\Unit\Model\Time\Month;
use SoureCode\Bundle\Unit\Model\Time\Second;
use SoureCode\Bundle\Unit\Model\Time\TimeUnitInterface;
use SoureCode\Bundle\Unit\Model\Time\Year;

class DurationTest extends TestCase
{

    public static function totalsDataProvider(): array
    {
        return [
            [0, "0", "0", "0", "0", "0", "0", "0"],
            [1, "1", "0.0166666666", "0.0002777777", "0.000011574", "0.0000016534", "0.0000003858", "0.0000000321"],
            [60, "60", "1", "0.0166666666", "0.0006944444", "0.0000992063", "0.0000231481", "0.000001929"],
            [3600, "3600", "60", "1", "0.0416666666", "0.0059523809", "0.0013888888", "0.0001157407"],
            [86400, "86400", "1440", "24", "1", "0.1428571428", "0.0333333333", "0.0027777777"],
            [604800, "604800", "10080", "168", "7", "1", "0.2333333333", "0.0194444444"],
            [2592000, "2592000", "43200", "720", "30", "4.2857142857", "1", "0.0833333333"],
            [31536000, "31536000", "525600", "8760", "365", "52.1428571428", "12.1666666666", "1.0138888888"],
            [90, "90", "1.5", "0.025", "0.0010416666", "0.0001488095", "0.0000347222", "0.0000028935"],
            [5400, "5400", "90", "1.5", "0.0625", "0.0089285714", "0.0020833333", "0.0001736111"],
            [1296000, "1296000", "21600", "360", "15", "2.1428571428", "0.5", "0.0416666666"],
            [15778458, "15778458", "262974.3", "4382.905", "182.6210416666", "26.088720238", "6.0873680555", "0.5072806712"],
            [47336400, "47336400", "788940", "13149", "547.875", "78.2678571428", "18.2625", "1.521875"],
            [94672800, "94672800", "1577880", "26298", "1095.75", "156.5357142857", "36.525", "3.04375"],
            [158112000, "158112000", "2635200", "43920", "1830", "261.4285714285", "61", "5.0833333333"],
            [12345678, "12345678", "205761.3", "3429.355", "142.8897916666", "20.4128273809", "4.7629930555", "0.3969160879"],
        ];
    }

    public static function formatDataProvider(): array
    {
        return [
            // Seconds
            [new Second(1), 's', '1'],
            [new Second(1), 'ss', '01'],
            [new Second(61), 'm:s', '1:1'],
            [new Second(61), 'mm:ss', '01:01'],

            // Minutes
            [new Minute(1), 'm:ss', '1:00'],
            [new Minute(1), 'mm:ss', '01:00'],
            [new Minute(90), 'h:m:s', '1:30:0'],
            [new Minute(90), 'hh:mm:ss', '01:30:00'],

            // Hours
            [new Hour(1), 'h:m:s', '1:0:0'],
            [new Hour(1), 'hh:mm:ss', '01:00:00'],
            [new Hour(25), 'D.h:mm', '1.1:00'],
            [new Hour(25), 'DD.hh:mm', '01.01:00'],

            // Days
            [new Day(1), 'D.h:m:s', '1.0:0:0'],
            [new Day(1), 'DD.hh:mm:ss', '01.00:00:00'],
            [new Day(45), 'M.D', '1.15'],
            [new Day(45), 'MM.DD', '01.15'],

            // Months
            [new Month(1), 'M.D', '1.0'],
            [new Month(1), 'MM.DD', '01.00'],
            [new Month(14), 'Y.M.D', '1.2.0'],
            [new Month(14), 'YY.MM.DD', '01.02.00'],

            // Years
            [new Year(1), 'Y.M.D', '1.0.0'],
            [new Year(1), 'YY.MM.DD', '01.00.00'],
            [new Year(2), 'YY-MM-DD', '02-00-00'],

            // Combined seconds
            [new Second(90061), 'D.h:m:s', '1.1:1:1'],
            [new Second(90061), 'DD.hh:mm:ss', '01.01:01:01'],

            // Combined years + seconds
            [new Second(31626061), 'Y-M-D h:m:s', '1-0-6 1:1:1'],
            [new Second(31626061), 'YY-MM-DD hh:mm:ss', '01-00-06 01:01:01'],

            // Zero
            [new Second(0), 'h:m:s', '0:0:0'],
            [new Second(0), 'hh:mm:ss', '00:00:00'],
            [new Year(0), 'Y.M.D', '0.0.0'],
            [new Year(0), 'YY.MM.DD', '00.00.00'],

            [new Second(31103999), 'Y-M-D h:m:s', '0-11-29 23:59:59'],
        ];
    }

    public static function floorDataProvider(): array
    {
        return [
            [0, Minute::class, 0],
            [59, Minute::class, 0],
            [60, Minute::class, 60],
            [61, Minute::class, 60],
            [123, Minute::class, 120],
            [123456789, Minute::class, 123456780],

            [0, Hour::class, 0],
            [3599, Hour::class, 0],
            [3600, Hour::class, 3600],
            [3661, Hour::class, 3600],
            [7322, Hour::class, 7200],
            [123456789, Hour::class, 123454800],

            [0, Day::class, 0],
            [86399, Day::class, 0],
            [86400, Day::class, 86400],
            [90000, Day::class, 86400],
            [123456789, Day::class, 123379200],

            [0, Month::class, 0],
            [2591999, Month::class, 0],
            [2592000, Month::class, 2592000],
            [2592123, Month::class, 2592000],
            [123456789, Month::class, 121824000],

            [0, Year::class, 0],
            [31103999, Year::class, 0],
            [31104000, Year::class, 31104000],
            [31105000, Year::class, 31104000],
            [123456789, Year::class, 93312000],
        ];
    }

    public static function ceilDataProvider()
    {
        return [
            // Minute
            [0, Minute::class, 0],
            [59, Minute::class, 60],
            [60, Minute::class, 60],
            [61, Minute::class, 120],
            [123, Minute::class, 180],
            [123456789, Minute::class, 123456840],

            // Hour
            [0, Hour::class, 0],
            [3599, Hour::class, 3600],
            [3600, Hour::class, 3600],
            [3661, Hour::class, 7200],
            [7322, Hour::class, 10800],
            [123456789, Hour::class, 123458400],

            // Day
            [0, Day::class, 0],
            [86399, Day::class, 86400],
            [86400, Day::class, 86400],
            [90000, Day::class, 172800],
            [123456789, Day::class, 123465600],

            // Month
            [0, Month::class, 0],
            [2591999, Month::class, 2592000],
            [2592000, Month::class, 2592000],
            [2592123, Month::class, 5184000],
            [123456789, Month::class, 124416000],

            // Year
            [0, Year::class, 0],
            [31103999, Year::class, 31104000],
            [31104000, Year::class, 31104000],
            [31105000, Year::class, 62208000],
            [123456789, Year::class, 124416000],
        ];
    }

    public static function roundDataProvider(): array
    {
        return [
            [0, Minute::class, 0],
            [29, Minute::class, 0],
            [30, Minute::class, 60],
            [59, Minute::class, 60],
            [60, Minute::class, 60],
            [89, Minute::class, 60],
            [123, Minute::class, 120],
            [123456789, Minute::class, 123456780],


            [0, Hour::class, 0],
            [1799, Hour::class, 0],
            [1800, Hour::class, 3600],
            [3599, Hour::class, 3600],
            [3660, Hour::class, 3600],
            [3661, Hour::class, 3600],
            [5400, Hour::class, 7200],
            [123456789, Hour::class, 123458400],

            [0, Day::class, 0],
            [43199, Day::class, 0],
            [43200, Day::class, 86400],
            [86400, Day::class, 86400],
            [129600, Day::class, 172800],
            [123456789, Day::class, 123465600],

            // Month (2592000s)
            [0, Month::class, 0],
            [1295999, Month::class, 0],
            [1296000, Month::class, 2592000],
            [2592000, Month::class, 2592000],
            [3888000, Month::class, 5184000],
            [123456789, Month::class, 124416000],

            [0, Year::class, 0],
            [15551999, Year::class, 0],
            [15552000, Year::class, 31104000],
            [31104000, Year::class, 31104000],
            [46656000, Year::class, 62208000],
            [123456789, Year::class, 124416000],
        ];
    }

    public static function constructorDataProvider(): array
    {
        return [
            [60, 60],
            [3600.0, 3600],
            ['86400', 86400],
            [new Number(90000), 90000],
            [new Hour(1), 3600],
            [new Day(2.5), 216000],
        ];
    }

    public static function addDataProvider(): array
    {
        return [
            // Adding int
            [new Duration(60), 30, 90],
            [new Duration(100.5), 59.5, 160],
            [new Duration('120'), '30', 150],
            [new Duration(200), new Number(100), 300],
            [new Duration(0), new Hour(1), 3600],
            [new Duration(3600), new Minute(30), 5400],
            [new Duration(0), new DateInterval('P1D'), 86400],
            [new Duration(100), new DateInterval('PT1H'), 3700],
            [new Duration(400), new Duration(100), 500],
            [new Duration(0), new Duration(123456789), 123456789],
        ];
    }

    public static function subDataProvider(): array
    {
        return [
            // Subtract int
            [new Duration(60), 30, 30],
            [new Duration(100.5), 59.5, 41],
            [new Duration('120'), '30', 90],
            [new Duration(200), new Number(100), 100],
            [new Duration(3600), new Hour(1), 0],
            [new Duration(3600), new Minute(30), 1800],
            [new Duration(86400), new DateInterval('P1D'), 0],
            [new Duration(3700), new DateInterval('PT1H'), 100],
            [new Duration(400), new Duration(100), 300],
            [new Duration(123456789), new Duration(123456789), 0],
        ];
    }

    public function testZero(): void
    {
        // Act
        $sut = Duration::zero();

        // Assert
        $this->assertEquals(0, $sut->totalSeconds()->getValue());
    }

    #[DataProvider('totalsDataProvider')]
    public function testTotals(
        int    $input,
        string $expectedSeconds,
        string $expectedMinutes,
        string $expectedHours,
        string $expectedDays,
        string $expectedWeeks,
        string $expectedMonths,
        string $expectedYears,
    ): void
    {
        // Arrange
        $sut = new Duration($input);

        // Act
        $seconds = $sut->totalSeconds()->getValue();
        $minutes = $sut->totalMinutes()->getValue();
        $hours = $sut->totalHours()->getValue();
        $days = $sut->totalDays()->getValue();
        $weeks = $sut->totalWeeks()->getValue();
        $months = $sut->totalMonths()->getValue();
        $years = $sut->totalYears()->getValue();

        // Assert
        $this->assertEquals($expectedSeconds, (string)$seconds, 'failed to assert seconds for ' . $input);
        $this->assertEquals($expectedMinutes, (string)$minutes, 'failed to assert minutes for ' . $input);
        $this->assertEquals($expectedHours, (string)$hours, 'failed to assert hours for ' . $input);
        $this->assertEquals($expectedDays, (string)$days, 'failed to assert days for ' . $input);
        $this->assertEquals($expectedWeeks, (string)$weeks, 'failed to assert weeks for ' . $input);
        $this->assertEquals($expectedMonths, (string)$months, 'failed to assert months for ' . $input);
        $this->assertEquals($expectedYears, (string)$years, 'failed to assert years for ' . $input);
    }

    #[DataProvider('formatDataProvider')]
    public function testFormat(TimeUnitInterface $input, string $format, string $expected): void
    {
        // Arrange
        $sut = new Duration($input);

        // Act
        $actual = $sut->format($format);

        // Assert
        $this->assertEquals($expected, $actual, 'failed to assert format for ' . $input::class . ' with format ' . $format);
    }

    #[DataProvider('floorDataProvider')]
    public function testFloor(int $input, string $unitClass, int $expected): void
    {
        // Arrange
        $sut = new Duration($input);

        // Act
        $actual = $sut->floor($unitClass);

        // Assert
        $this->assertEquals(
            $expected,
            $actual->totalSeconds()->getValue(),
            "Failed asserting floor result for input $input with unit $unitClass"
        );
    }

    #[DataProvider('ceilDataProvider')]
    public function testCeil(int $input, string $unitClass, int $expected): void
    {
        // Arrange
        $sut = new Duration($input);

        // Act
        $actual = $sut->ceil($unitClass);

        // Assert
        $this->assertEquals(
            $expected,
            $actual->totalSeconds()->getValue(),
            "Failed asserting ceil result for input $input with unit $unitClass"
        );
    }

    #[DataProvider('roundDataProvider')]
    public function testRound(int $input, string $unitClass, int $expected): void
    {
        // Arrange
        $sut = new Duration($input);

        // Act
        $actual = $sut->round($unitClass);

        // Assert
        $this->assertEquals(
            $expected,
            $actual->totalSeconds()->getValue(),
            "Failed asserting round result for input $input with unit $unitClass"
        );
    }

    #[DataProvider('constructorDataProvider')]
    public function testConstruct(TimeUnitInterface|Number|string|float|int $input, int $expected): void
    {
        // Act
        $sut = new Duration($input);

        // Assert
        $this->assertEquals($expected, $sut->totalSeconds()->getValue());
    }

    #[DataProvider('addDataProvider')]
    public function testAdd(Duration $base, Duration|DateInterval|TimeUnitInterface|Number|string|float|int $add, int $expected): void
    {
        // Act
        $result = $base->add($add);

        // Assert
        $this->assertEquals($expected, $result->totalSeconds()->getValue());
    }

    #[DataProvider('subDataProvider')]
    public function testSub(Duration $base, Duration|DateInterval|TimeUnitInterface|Number|string|float|int $add, int $expected): void
    {
        // Act
        $result = $base->sub($add);

        // Assert
        $this->assertEquals($expected, $result->totalSeconds()->getValue());
    }
}
