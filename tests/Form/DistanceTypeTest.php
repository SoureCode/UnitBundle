<?php

namespace Form;

use SoureCode\Bundle\Unit\Form\DistanceType;
use SoureCode\Bundle\Unit\Model\Distance;
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
        $this->assertEquals('1.06dm', $form->getData()->format());
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
        $distance = new Distance(new Centimeter("10.6"));
        $form = $this->factory->create(DistanceType::class, $distance);

        // Act
        $view = $form->createView();

        // Assert
        $this->assertEquals('1.06', $view->children['value']->vars['value']);
        $this->assertEquals(LengthUnitType::DECIMETER->value, $view->children['unit']->vars['value']);
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