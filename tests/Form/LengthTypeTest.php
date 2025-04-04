<?php

namespace SoureCode\Bundle\Unit\Tests\Form;

use SoureCode\Bundle\Unit\Form\UnitType;
use SoureCode\Bundle\Unit\Model\AbstractUnit;
use SoureCode\Bundle\Unit\Model\Length\AbstractLengthUnit;
use SoureCode\Bundle\Unit\Model\Length\Centimeter;
use SoureCode\Bundle\Unit\Model\Length\Kilometer;
use SoureCode\Bundle\Unit\Model\Length\Meter;
use SoureCode\Bundle\Unit\Model\Length\LengthUnitType;
use Symfony\Component\Form\Test\TypeTestCase;

class LengthTypeTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        // Arrange
        $form = $this->factory->create(UnitType::class);

        // Act
        $form->submit([
            'value' => '10.6',
            'unit_type' => LengthUnitType::CENTIMETER->value,
        ]);

        // Assert
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals(new Centimeter("10.6"), $form->getData());
    }

    public function testConfigureTargetUnitType(): void
    {
        // Arrange
        $form = $this->factory->create(UnitType::class, null, [
            'target_unit_class' => Kilometer::class,
        ]);
        $expected = new Kilometer("321.654");

        // Act
        $form->submit([
            'value' => '321654',
            'unit_type' => LengthUnitType::METER->value,
        ]);

        // Assert
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expected, $form->getData());
    }

    public function testSubmitNull(): void
    {
        // Arrange
        $form = $this->factory->create(UnitType::class);

        // Act
        $form->submit(null);

        // Assert
        $this->assertTrue($form->isSynchronized());
        $this->assertNull($form->getData());
    }

    public function testWithPreData(): void
    {
        // Arrange
        $form = $this->factory->create(UnitType::class, new Centimeter("10.6"));

        // Act
        $view = $form->createView();

        // Assert
        $this->assertEquals('10.6', $view->children['value']->vars['value']);
        $this->assertEquals(LengthUnitType::CENTIMETER->value, $view->children['unit_type']->vars['value']);
    }

    public function testWithNullPreData(): void
    {
        // Arrange
        $form = $this->factory->create(UnitType::class, null);

        // Act
        $view = $form->createView();

        // Assert
        $this->assertNull($view->vars['value']);
        $this->assertEmpty($view->children['value']->vars['value']);
        $this->assertEmpty('', $view->children['unit_type']->vars['value']);
    }
}