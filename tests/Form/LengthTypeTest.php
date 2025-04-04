<?php

namespace SoureCode\Bundle\Unit\Tests\Form;

use SoureCode\Bundle\Unit\Form\LengthType;
use SoureCode\Bundle\Unit\Model\Metric\Converter;
use SoureCode\Bundle\Unit\Model\Metric\Factory;
use SoureCode\Bundle\Unit\Model\Metric\Length\Centimeter;
use SoureCode\Bundle\Unit\Model\Metric\Length\Kilometer;
use SoureCode\Bundle\Unit\Model\Metric\Length\Meter;
use SoureCode\Bundle\Unit\Model\Metric\Prefix;
use Symfony\Component\Form\Test\TypeTestCase;

class LengthTypeTest extends TypeTestCase
{
    protected function getTypes(): array
    {
        $mapping = [
            Prefix::KILO->value => Kilometer::class,
            Prefix::BASE->value => Meter::class,
            Prefix::CENTI->value => Centimeter::class,
        ];

        $factory = new Factory($mapping);
        $converter = new Converter($mapping);

        return [
            new LengthType(
                mapping: $mapping,
                factory: $factory,
                converter: $converter,
            ),
        ];
    }

    public function testSubmitValidData(): void
    {
        // Arrange
        $form = $this->factory->create(LengthType::class);

        $expected = new Centimeter("10.6");

        // Act
        $form->submit([
            'value' => '10.6',
            'prefix' => 'centi',
        ]);

        // Assert
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expected, $form->getData());
    }

    public function testConfigureTargetPrefix(): void
    {
        // Arrange
        $form = $this->factory->create(LengthType::class, null, ['target_prefix' => Prefix::KILO]);
        $expected = new Kilometer("321.654");

        // Act
        $form->submit([
            'value' => '321654',
            'prefix' => Prefix::BASE->value,
        ]);

        // Assert
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expected, $form->getData());
    }

    public function testSubmitNull(): void
    {
        // Arrange
        $form = $this->factory->create(LengthType::class);

        // Act
        $form->submit(null);

        // Assert
        $this->assertTrue($form->isSynchronized());
        $this->assertNull($form->getData());
    }

    public function testWithPreData(): void
    {
        // Arrange
        $form = $this->factory->create(LengthType::class, new Centimeter("10.6"));

        // Act
        $view = $form->createView();

        // Assert
        $this->assertEquals('10.6', $view->children['value']->vars['value']);
        $this->assertEquals(Prefix::CENTI->value, $view->children['prefix']->vars['value']);
    }

    public function testWithNullPreData(): void
    {
        // Arrange
        $form = $this->factory->create(LengthType::class, null);

        // Act
        $view = $form->createView();

        // Assert
        $this->assertNull($view->vars['value']);
        $this->assertEmpty($view->children['value']->vars['value']);
        $this->assertSame('base', $view->children['prefix']->vars['value']);
    }
}