<?php

namespace SoureCode\Bundle\Unit\Model;

use BcMath\Number;
use SoureCode\Bundle\Unit\Model\Time\Day;
use SoureCode\Bundle\Unit\Model\Time\Hour;
use SoureCode\Bundle\Unit\Model\Time\Minute;
use SoureCode\Bundle\Unit\Model\Time\Month;
use SoureCode\Bundle\Unit\Model\Time\Second;
use SoureCode\Bundle\Unit\Model\Time\TimeUnitInterface;
use SoureCode\Bundle\Unit\Model\Time\Week;
use SoureCode\Bundle\Unit\Model\Time\Year;

final class Duration implements \Stringable
{
    public const string FORMAT_SHORT = 'hh:mm';
    public const string FORMAT_MEDIUM = 'hh:mm:ss';
    public const string FORMAT_LONG = 'DD.hh:mm:ss';
    public const string FORMAT_FULL = 'YY.MM.DD.hh:mm:ss';
    public static string $DEFAULT_FORMAT = self::FORMAT_MEDIUM;

    /**
     * @var array{year: int, month: int, day: int, hour: int, minute: int, second: int}|null
     */
    private ?array $decomposed = null;

    private Second $value;

    public function __construct(TimeUnitInterface|Number|string|float|int $value)
    {
        if ($value instanceof TimeUnitInterface) {
            $this->value = $value->convert(Second::class);
        } else {
            $this->value = new Second($value);
        }
    }

    public static function zero(): self
    {
        return new self(new Second(0));
    }

    public static function create(TimeUnitInterface|Number|string|float|int $value): self
    {
        return new self($value);
    }

    public function years(): int
    {
        return $this->decompose()['year'];
    }

    /**
     * @return array{year: int, month: int, day: int, hour: int, minute: int, second: int}
     */
    public function decompose(): array
    {
        if (null === $this->decomposed) {
            $delta = clone $this;

            $years = $delta->totalYears()->round(0, \RoundingMode::TowardsZero);
            $delta = $delta->sub($years);

            $months = $delta->totalMonths()->round(0, \RoundingMode::TowardsZero);
            $delta = $delta->sub($months);

            $days = $delta->totalDays()->round(0, \RoundingMode::TowardsZero);
            $delta = $delta->sub($days);

            $hours = $delta->totalHours()->round(0, \RoundingMode::TowardsZero);
            $delta = $delta->sub($hours);

            $minutes = $delta->totalMinutes()->round(0, \RoundingMode::TowardsZero);
            $delta = $delta->sub($minutes);

            $seconds = $delta->totalSeconds()->round(0, \RoundingMode::TowardsZero);

            $this->decomposed = [
                'year' => (int) $years->getValue()->value,
                'month' => (int) $months->getValue()->value,
                'day' => (int) $days->getValue()->value,
                'hour' => (int) $hours->getValue()->value,
                'minute' => (int) $minutes->getValue()->value,
                'second' => (int) $seconds->getValue()->value,
            ];
        }

        return $this->decomposed;
    }

    /**
     * @param class-string<TimeUnitInterface> $unitClass
     */
    public function round(string $unitClass = Minute::class): self
    {
        return new self(
            $this->value->convert($unitClass)
                ->round()
                ->convert(Second::class)
        );
    }

    public function totalYears(): Year
    {
        return $this->value->convert(Year::class);
    }

    public function sub(self|\DateInterval|TimeUnitInterface|Number|string|float|int $duration): self
    {
        if ($duration instanceof \DateInterval) {
            $duration = self::fromDateInterval($duration);
        }

        if ($duration instanceof TimeUnitInterface) {
            $second = $duration->convert(Second::class);

            return new self($this->value->getValue()->sub($second->getValue()));
        }

        if ($duration instanceof self) {
            return new self($this->value->getValue()->sub($duration->value->getValue()));
        }

        $second = new Second($duration);

        return new self($this->value->getValue()->sub($second->getValue()));
    }

    private static function fromDateInterval(\DateInterval $duration): self
    {
        $now = new \DateTimeImmutable();
        $future = $now->add($duration);
        $seconds = $future->getTimestamp() - $now->getTimestamp();

        return new self(new Second($seconds));
    }

    public function add(self|\DateInterval|TimeUnitInterface|Number|string|float|int $duration): self
    {
        if ($duration instanceof \DateInterval) {
            $duration = self::fromDateInterval($duration);
        }

        if ($duration instanceof TimeUnitInterface) {
            $second = $duration->convert(Second::class);

            return new self($this->value->getValue()->add($second->getValue()));
        }

        if ($duration instanceof self) {
            return new self($this->value->getValue()->add($duration->value->getValue()));
        }

        $second = new Second($duration);

        return new self($this->value->getValue()->add($second->getValue()));
    }

    public function getValue(): TimeUnitInterface
    {
        return $this->value->clone();
    }

    public function totalMonths(): Month
    {
        return $this->value->convert(Month::class);
    }

    public function totalDays(): Day
    {
        return $this->value->convert(Day::class);
    }

    public function totalHours(): Hour
    {
        return $this->value->convert(Hour::class);
    }

    public function totalMinutes(): Minute
    {
        return $this->value->convert(Minute::class);
    }

    public function totalSeconds(): Second
    {
        return $this->value;
    }

    public function months(): int
    {
        return $this->decompose()['month'];
    }

    public function days(): int
    {
        return $this->decompose()['day'];
    }

    public function hours(): int
    {
        return $this->decompose()['hour'];
    }

    public function minutes(): int
    {
        return $this->decompose()['minute'];
    }

    public function seconds(): int
    {
        return $this->decompose()['second'];
    }

    public function totalWeeks(): Week
    {
        return $this->value->convert(Week::class);
    }

    /**
     * @param class-string<TimeUnitInterface> $className
     */
    public function floor(string $className = Minute::class): self
    {
        return new self(
            $this->value->convert($className)
                ->floor()
                ->convert(Second::class)
        );
    }

    /**
     * @param class-string<TimeUnitInterface> $className
     */
    public function ceil(string $className = Minute::class): self
    {
        return new self(
            $this->value->convert($className)
                ->ceil()
                ->convert(Second::class)
        );
    }

    public function compare(Distance $distance): int
    {
        return $this->value->compare($distance->getValue());
    }

    public function __toString()
    {
        return $this->format();
    }

    /**
     * Formats the duration according to the specified format string.
     *
     * Available format tokens:
     * - Y: Years (zero-padded)
     * - M: Months (zero-padded)
     * - D: Days (zero-padded)
     * - h: Hours (zero-padded)
     * - m: Minutes (zero-padded)
     * - s: Seconds (zero-padded)
     *
     * Predefined formats:
     * - FORMAT_SHORT: 'hh:mm'
     * - FORMAT_MEDIUM: 'hh:mm:ss'
     * - FORMAT_LONG: 'DD.hh:mm:ss'
     * - FORMAT_FULL: 'YY.MM.DD.hh:mm:ss'
     *
     * @param string|null $format The format string to use. If null, uses the default format (self::$DEFAULT_FORMAT)
     *
     * @return string The formatted duration string
     *
     * @throws \RuntimeException When formatting fails
     */
    public function format(?string $format = null): string
    {
        if (null === $format) {
            $format = self::$DEFAULT_FORMAT;
        }

        /**
         * @var array{years: int, months: int, days: int, hours: int, minutes: int, seconds: int} $decomposed
         */
        $decomposed = $this->decompose();

        /**
         * @var string|null $formatted
         */
        $formatted = preg_replace_callback('/([YMDhms])\1*/', static function (array $matches) use ($decomposed) {
            /**
             * @var array<string, string> $mapping
             */
            static $mapping = [
                'Y' => 'year',
                'M' => 'month',
                'D' => 'day',
                'h' => 'hour',
                'm' => 'minute',
                's' => 'second',
            ];

            $token = $matches[0];
            $component = $mapping[$token[0]];
            $value = $decomposed[$component];

            return str_pad((string) $value, \strlen($token), '0', \STR_PAD_LEFT);
        }, $format);

        if (null === $formatted) {
            throw new \RuntimeException('Failed to format date duration');
        }

        return $formatted;
    }
}
