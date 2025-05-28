<?php

namespace SoureCode\Bundle\Unit\Model\Time;

use BcMath\Number;

enum TimeUnitType: string
{
    case SECOND = 'second';
    case MINUTE = 'minute';
    case HOUR = 'hour';
    case DAY = 'day';
    case WEEK = 'week';
    case WEEKEND = 'weekend';
    case WORKWEEK = 'workweek';
    case MONTH = 'month';
    case YEAR = 'year';
    case MILLISECOND = 'millisecond';
    case MICROSECOND = 'microsecond';
    case NANOSECOND = 'nanosecond';
    case PICOSECOND = 'picosecond';

    public static function fromString(string $unit): TimeUnitType
    {
        $normalized = self::normalize($unit);

        return self::from($normalized);
    }

    private static function normalize(string $unit): string
    {
        return match (mb_strtolower($unit)) {
            'picosecond', 'picoseconds' => 'picosecond',
            'nanosecond', 'nanoseconds' => 'nanosecond',
            'microsecond', 'microseconds' => 'microsecond',
            'millisecond', 'milliseconds' => 'millisecond',
            'second', 'seconds' => 'second',
            'minute', 'minutes' => 'minute',
            'hour', 'hours' => 'hour',
            'day', 'days' => 'day',
            'week', 'weeks' => 'week',
            'month', 'months' => 'month',
            'year', 'years' => 'year',
            default => $unit,
        };
    }

    /**
     * @return class-string<TimeUnitInterface>
     */
    public function toClassName(): string
    {
        return match ($this) {
            self::YEAR => Year::class,
            self::MONTH => Month::class,
            self::WEEK => Week::class,
            self::DAY => Day::class,
            self::HOUR => Hour::class,
            self::MINUTE => Minute::class,
            self::SECOND => Second::class,
            self::MILLISECOND => Millisecond::class,
            self::MICROSECOND => Microsecond::class,
            self::NANOSECOND => Nanosecond::class,
            self::PICOSECOND => Picosecond::class,
            default => throw new \Exception('Unsupported time unit'),
        };
    }

    public function create(Number|string|int|float $value): TimeUnitInterface
    {
        return new ($this->toClassName())($value);
    }
}
