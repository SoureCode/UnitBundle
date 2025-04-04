<?php

namespace SoureCode\Bundle\Unit\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\FloatType;
use SoureCode\Bundle\Unit\Model\Metric\ConverterInterface;
use SoureCode\Bundle\Unit\Model\Metric\FactoryInterface;
use SoureCode\Bundle\Unit\Model\Metric\Length\LengthUnitInterface;
use SoureCode\Bundle\Unit\Model\Metric\Length\Picometer;
use SoureCode\Bundle\Unit\Model\Metric\NormalizerInterface;
use SoureCode\Bundle\Unit\Model\Metric\UnitInterface;

class LengthType extends FloatType
{
    public const string NAME = 'length';

    /**
     * @var class-string<LengthUnitInterface>
     */
    public static string $databaseUnitClass = Picometer::class;

    public static ConverterInterface $converter;
    public static FactoryInterface $factory;
    public static NormalizerInterface $normalizer;

    public function convertToPHPValue($value, AbstractPlatform $platform): ?UnitInterface
    {
        $value = parent::convertToPHPValue($value, $platform);

        if (null === $value) {
            return null;
        }

        $databaseUnit = self::$factory->create($value, self::$databaseUnitClass::getPrefix());

        return self::$normalizer->normalize($databaseUnit);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof UnitInterface) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), [UnitInterface::class]);
        }

        $databaseUnit = self::$converter->convert($value, self::$databaseUnitClass::getPrefix());

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
