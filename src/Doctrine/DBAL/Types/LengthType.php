<?php

namespace SoureCode\Bundle\Unit\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\FloatType;
use SoureCode\Bundle\Unit\Model\Length\LengthUnitInterface;
use SoureCode\Bundle\Unit\Model\Length\Picometer;
use SoureCode\Bundle\Unit\Model\UnitInterface;

class LengthType extends FloatType
{
    public const string NAME = 'length';

    /**
     * @var class-string<LengthUnitInterface>
     */
    public static string $databaseUnitClass = Picometer::class;

    public function convertToPHPValue($value, AbstractPlatform $platform): ?UnitInterface
    {
        $value = parent::convertToPHPValue($value, $platform);

        if (null === $value) {
            return null;
        }

        /**
         * @var UnitInterface $databaseUnit
         */
        $databaseUnit = new self::$databaseUnitClass($value);

        return $databaseUnit->normalize();
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof UnitInterface) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), [UnitInterface::class]);
        }

        $databaseUnit = $value->convert(self::$databaseUnitClass);

        return parent::convertToDatabaseValue((string) $databaseUnit->getValue(), $platform);
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
