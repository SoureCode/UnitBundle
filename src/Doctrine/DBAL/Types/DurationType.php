<?php

namespace SoureCode\Bundle\Unit\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Exception\InvalidType;
use Doctrine\DBAL\Types\Type;
use SoureCode\Bundle\Unit\Model\Duration;
use SoureCode\Bundle\Unit\Model\Time\Picosecond;
use SoureCode\Bundle\Unit\Model\Time\TimeUnitInterface;
use SoureCode\Bundle\Unit\Model\UnitInterface;

class DurationType extends Type
{
    public const string NAME = 'duration';

    /**
     * @var class-string<TimeUnitInterface>
     */
    public static string $databaseUnitClass = Picosecond::class;

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getFloatDeclarationSQL($column);
    }

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

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof Duration) {
            throw InvalidType::new($value, static::NAME, [UnitInterface::class]);
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
