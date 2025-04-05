<?php

namespace Form;

use SoureCode\Bundle\Unit\Form\DistanceType;
use SoureCode\Bundle\Unit\Model\Length\Centimeter;
use SoureCode\Bundle\Unit\Model\Length\Kilometer;
use SoureCode\Bundle\Unit\Model\Length\LengthUnitType;
use Symfony\Component\Form\Test\TypeTestCase;

class DistanceTypeTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        // Arrange
        $form = $this->factory->create(DistanceType::class);

        // Act
        $form->submit([
            'value' => '10.6',
            'unit' => LengthUnitType::CENTIMETER->value,
        ]);

        // Assert
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals('106mm', $form->getData()->format());
    }

    public function testSubmitNull(): void
    {
        // Arrange
        $form = $this->factory->create(DistanceType::class);

        // Act
        $form->submit(null);

        // Assert
        $this->assertTrue($form->isSynchronized());
        $this->assertNull($form->getData());
    }

    public function testWithPreData(): void
    {
        // Arrange
        $form = $this->factory->create(DistanceType::class, new Centimeter("10.6"));

        // Act
        $view = $form->createView();

        // Assert
        $this->assertEquals('10.6', $view->children['value']->vars['value']);
        $this->assertEquals(LengthUnitType::CENTIMETER->value, $view->children['unit']->vars['value']);
    }

    public function testWithNullPreData(): void
    {
        // Arrange
        $form = $this->factory->create(DistanceType::class, null);

        // Act
        $view = $form->createView();

        // Assert
        $this->assertNull($view->vars['value']);
        $this->assertEmpty($view->children['value']->vars['value']);
        $this->assertEmpty('', $view->children['unit']->vars['value']);
    }
}