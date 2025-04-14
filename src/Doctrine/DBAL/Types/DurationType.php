<?php

namespace SoureCode\Bundle\Unit\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\FloatType;
use SoureCode\Bundle\Unit\Model\Duration;
use SoureCode\Bundle\Unit\Model\Time\Picosecond;
use SoureCode\Bundle\Unit\Model\Time\TimeUnitInterface;
use SoureCode\Bundle\Unit\Model\UnitInterface;

class DurationType extends FloatType
{
    public const string NAME = 'duration';

    /**
     * @var class-string<TimeUnitInterface>
     */
    public static string $databaseUnitClass = Picosecond::class;

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Duration
    {
        $value = parent::convertToPHPValue($value, $platform);

        if (null === $value) {
            return null;
        }

        /**
         * @var TimeUnitInterface $databaseUnit
         */
        $databaseUnit = new static::$databaseUnitClass($value);

        return new Duration($databaseUnit);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof Duration) {
            throw ConversionException::conversionFailedInvalidType($value, static::NAME, [UnitInterface::class]);
        }

        /**
         * @var TimeUnitInterface $databaseUnit
         */
        $databaseUnit = $value->getValue()->convert(static::$databaseUnitClass);

        return parent::convertToDatabaseValue((string) $databaseUnit->getValue(), $platform);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    public function getName(): string
    {
        return static::NAME;
    }
}
