<?php

namespace Model;

use DateTimeImmutable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SoureCode\Bundle\Unit\Model\Interval;
use SoureCode\Bundle\Unit\Model\Time\TimeUnitType;

class IntervalTest extends TestCase
{
    public static function createDataProvider(): array
    {
        return [
            [
                DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2023-10-01 12:32:16'),
                TimeUnitType::MINUTE,
                DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2023-10-01 12:32:00'),
                DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2023-10-01 12:32:59'),
            ],
            [
                DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2023-10-01 14:25:33'),
                TimeUnitType::HOUR,
                DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2023-10-01 14:00:00'),
                DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2023-10-01 14:59:59'),
            ],
            [
                DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2023-10-02 03:15:00'),
                TimeUnitType::DAY,
                DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2023-10-02 00:00:00'),
                DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2023-10-02 23:59:59'),
            ],
            [
                DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2023-10-04 10:00:00'),
                TimeUnitType::WEEK,
                DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2023-10-02 00:00:00'),
                DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2023-10-08 23:59:59'),
            ],
            [
                DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2023-10-03 10:00:00'),
                TimeUnitType::WEEKEND,
                DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2023-10-06 00:00:00'),
                DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2023-10-08 23:59:59'),
            ],
            [
                DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2023-10-04 14:00:00'),
                TimeUnitType::WORKWEEK,
                DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2023-10-02 00:00:00'),
                DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2023-10-06 23:59:59'),
            ],
            [
                DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2023-10-10 08:00:00'),
                TimeUnitType::MONTH,
                DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2023-10-01 00:00:00'),
                DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2023-10-31 23:59:59'),
            ],
            [
                DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2023-04-15 17:00:00'),
                TimeUnitType::YEAR,
                DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2023-01-01 00:00:00'),
                DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2023-12-31 23:59:59'),
            ],
        ];
    }

    #[DataProvider('createDataProvider')]
    public function testCreate(DateTimeImmutable $base, TimeUnitType $unit, DateTimeImmutable $expectedStartedAt, DateTimeImmutable $expectedStoppedAt): void
    {
        $dateTimeSpan = Interval::create($base, $unit);

        self::assertEquals($expectedStartedAt, $dateTimeSpan->getStartedAt(), 'failed to assert startedAt for '.$unit->name);
        self::assertEquals($expectedStoppedAt, $dateTimeSpan->getStoppedAt(), 'failed to assert stoppedAt for '.$unit->name);
    }
}
