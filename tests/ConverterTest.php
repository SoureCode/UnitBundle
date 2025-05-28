<?php

use PHPUnit\Framework\TestCase;
use SoureCode\Bundle\Unit\Converter\LengthConverter;
use SoureCode\Bundle\Unit\Converter\TimeConverter;
use SoureCode\Bundle\Unit\Model\Length\Meter;
use SoureCode\Bundle\Unit\Model\Time\Year;

class ConverterTest extends TestCase
{
    public function testInvalidLengthConvert(): void
    {
        // Arrange
        $base = new Meter('3.14');

        // Act & Assert
        $this->expectException(InvalidArgumentException::class);

        LengthConverter::convert($base, Year::class);
    }

    public function testInvalidTimeConvert(): void
    {
        // Arrange
        $base = new Year('10');

        // Act & Assert
        $this->expectException(InvalidArgumentException::class);

        TimeConverter::convert($base, Meter::class);
    }
}
