<?php

namespace SoureCode\Bundle\Unit\Model;

use SoureCode\Bundle\Unit\Model\Time\TimeUnitType;

final readonly class Interval
{
    public function __construct(
        private \DateTimeImmutable $startedAt,
        private \DateTimeImmutable $stoppedAt,
    ) {
    }

    public static function create(
        \DateTimeImmutable $base,
        TimeUnitType $unit,
    ): self {
        $startedAt = $base;
        $stoppedAt = $base;

        switch ($unit) {
            case TimeUnitType::MINUTE:
                $startedAt = $base->setTime((int) $base->format('H'), (int) $base->format('i'), 0);
                $stoppedAt = $base->setTime((int) $base->format('H'), (int) $base->format('i'), 59);
                break;
            case TimeUnitType::HOUR:
                $startedAt = $base->setTime((int) $base->format('H'), 0, 0);
                $stoppedAt = $base->setTime((int) $base->format('H'), 59, 59);
                break;
            case TimeUnitType::DAY:
                $startedAt = $base->setTime(0, 0, 0);
                $stoppedAt = $base->setTime(23, 59, 59);
                break;
            case TimeUnitType::WEEK:
                $startedAt = $base->modify('monday this week')->setTime(0, 0, 0);
                $stoppedAt = $base->modify('sunday this week')->setTime(23, 59, 59);
                break;
            case TimeUnitType::WEEKEND:
                $startedAt = $base->modify('friday this week')->setTime(0, 0, 0);
                $stoppedAt = $base->modify('sunday this week')->setTime(23, 59, 59);
                break;
            case TimeUnitType::WORKWEEK:
                $startedAt = $base->modify('monday this week')->setTime(0, 0, 0);
                $stoppedAt = $base->modify('friday this week')->setTime(23, 59, 59);
                break;
            case TimeUnitType::MONTH:
                $startedAt = $base->modify('first day of this month')->setTime(0, 0, 0);
                $stoppedAt = $base->modify('last day of this month')->setTime(23, 59, 59);
                break;
            case TimeUnitType::YEAR:
                $startedAt = $base->modify('first day of january this year')->setTime(0, 0, 0);
                $stoppedAt = $base->modify('last day of december this year')->setTime(23, 59, 59);
                break;
            default:
                throw new \InvalidArgumentException(TimeUnitType::class.'::'.$unit->name.' is not supported');
        }

        return new self($startedAt, $stoppedAt);
    }

    public function getStartedAt(): \DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function getStoppedAt(): \DateTimeImmutable
    {
        return $this->stoppedAt;
    }

    public function getDuration(): Duration
    {
        $seconds = $this->stoppedAt->getTimestamp() - $this->startedAt->getTimestamp();

        return new Duration($seconds);
    }
}
