<?php

namespace SoureCode\Bundle\Unit\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\FloatType;
use SoureCode\Bundle\Unit\Model\Distance;
use SoureCode\Bundle\Unit\Model\Length\LengthUnitInterface;
use SoureCode\Bundle\Unit\Model\Length\Picometer;
use SoureCode\Bundle\Unit\Model\UnitInterface;

class DistanceType extends FloatType
{
    public const string NAME = 'distance';

    /**
     * @var class-string<LengthUnitInterface>
     */
    public static string $databaseUnitClass = Picometer::class;

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Distance
    {
        $value = parent::convertToPHPValue($value, $platform);

        if (null === $value) {
            return null;
        }

        /**
         * @var LengthUnitInterface $databaseUnit
         */
        $databaseUnit = new static::$databaseUnitClass($value);

        return new Distance($databaseUnit->normalize());
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof Distance) {
            throw ConversionException::conversionFailedInvalidType($value, static::NAME, [UnitInterface::class]);
        }

        /**
         * @var LengthUnitInterface $databaseUnit
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
