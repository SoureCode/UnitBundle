<?php

namespace SoureCode\Bundle\Unit\Model;

use BcMath\Number;
use JetBrains\PhpStorm\ArrayShape;
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
     * @var array{years: int, months: int, days: int, hours: int, minutes: int, seconds: int}|null
     */
    private ?array $decomposed = null;

    private TimeUnitInterface $value;

    public function __construct(UnitInterface|TimeUnitInterface|Number|string|float|int $value)
    {
        if ($value instanceof TimeUnitInterface) {
            $this->value = $value->convert(Second::class);
        } elseif ($value instanceof UnitInterface) {
            throw new \InvalidArgumentException(\sprintf('Unit must be of type %s.', TimeUnitInterface::class));
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

    /**
     * @return array{years: int, months: int, days: int, hours: int, minutes: int, seconds: int}
     */
    #[ArrayShape([
        'years' => 'int',
        'months' => 'int',
        'days' => 'int',
        'hours' => 'int',
        'minutes' => 'int',
        'seconds' => 'int',
    ])]
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
                'years' => (int) $years->getValue()->value,
                'months' => (int) $months->getValue()->value,
                'days' => (int) $days->getValue()->value,
                'hours' => (int) $hours->getValue()->value,
                'minutes' => (int) $minutes->getValue()->value,
                'seconds' => (int) $seconds->getValue()->value,
            ];
        }

        return $this->decomposed;
    }

    public function years(): int
    {
        return $this->decompose()['years'];
    }

    public function months(): int
    {
        return $this->decompose()['months'];
    }

    public function days(): int
    {
        return $this->decompose()['days'];
    }

    public function hours(): int
    {
        return $this->decompose()['hours'];
    }

    public function minutes(): int
    {
        return $this->decompose()['minutes'];
    }

    public function seconds(): int
    {
        return $this->decompose()['seconds'];
    }

    public function totalYears(): TimeUnitInterface
    {
        return $this->value->convert(Year::class);
    }

    public function totalMonths(): TimeUnitInterface
    {
        return $this->value->convert(Month::class);
    }

    public function totalWeeks(): TimeUnitInterface
    {
        return $this->value->convert(Week::class);
    }

    public function totalDays(): TimeUnitInterface
    {
        return $this->value->convert(Day::class);
    }

    public function totalHours(): TimeUnitInterface
    {
        return $this->value->convert(Hour::class);
    }

    public function totalMinutes(): TimeUnitInterface
    {
        return $this->value->convert(Minute::class);
    }

    public function totalSeconds(): TimeUnitInterface
    {
        return $this->value;
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
        $formatted = preg_replace_callback('/([YMDhms])\1*/', static function ($matches) use ($decomposed) {
            static $mapping = [
                'Y' => 'years',
                'M' => 'months',
                'D' => 'days',
                'h' => 'hours',
                'm' => 'minutes',
                's' => 'seconds',
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

    private static function fromDateInterval(\DateInterval $duration): self
    {
        $now = new \DateTimeImmutable();
        $future = $now->add($duration);
        $seconds = $future->getTimestamp() - $now->getTimestamp();

        return new self(new Second($seconds));
    }

    public function sub(self|\DateInterval|TimeUnitInterface|Number|string|float|int $duration): self
    {
        if ($duration instanceof \DateInterval) {
            $duration = self::fromDateInterval($duration);
        }

        if ($duration instanceof TimeUnitInterface) {
            $duration = $duration->convert(Second::class);

            return new self($this->value->getValue()->sub($duration->getValue()));
        }

        if ($duration instanceof self) {
            return new self($this->value->getValue()->sub($duration->value->getValue()));
        }

        $second = new Second($duration);

        return new self($this->value->getValue()->sub($second->getValue()));
    }

    public function add(self|\DateInterval|TimeUnitInterface|Number|string|float|int $duration): self
    {
        if ($duration instanceof \DateInterval) {
            $duration = self::fromDateInterval($duration);
        }

        if ($duration instanceof TimeUnitInterface) {
            $duration = $duration->convert(Second::class);

            return new self($this->value->getValue()->add($duration->getValue()));
        }

        if ($duration instanceof self) {
            return new self($this->value->getValue()->add($duration->value->getValue()));
        }

        $second = new Second($duration);

        return new self($this->value->getValue()->add($second->getValue()));
    }

    public function floor(string $unitClass = Minute::class): self
    {
        return new self(
            $this->value->convert($unitClass)
                ->round(0, \RoundingMode::TowardsZero)
                ->convert(Second::class)
        );
    }

    public function ceil(string $unitClass = Minute::class): self
    {
        return new self(
            $this->value->convert($unitClass)
                ->round(0, \RoundingMode::AwayFromZero)
                ->convert(Second::class)
        );
    }

    public function round(string $unitClass = Minute::class): self
    {
        return new self(
            $this->value->convert($unitClass)
                ->round(0)
                ->convert(Second::class)
        );
    }

    public function compare(Distance $distance): int
    {
        return $this->value->compare($distance->getValue());
    }

    public function getValue(): TimeUnitInterface
    {
        return $this->value->clone();
    }

    public function __toString()
    {
        return $this->format();
    }
}
