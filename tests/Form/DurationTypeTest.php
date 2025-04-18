<?php

namespace Form;

use SoureCode\Bundle\Unit\Form\DurationType;
use SoureCode\Bundle\Unit\Model\Duration;
use SoureCode\Bundle\Unit\Model\Time\Hour;
use SoureCode\Bundle\Unit\Model\Time\Second;
use Symfony\Component\Form\Test\TypeTestCase;

class DurationTypeTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        // Arrange
        $form = $this->factory->create(DurationType::class);

        // Act
        $form->submit([
            'hour' => '3',
            'minute' => '34',
        ]);

        // Assert
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals(new Second("12840"), $form->getData()->getValue());
    }

    public function testConfigureAnotherUnit(): void
    {
        // Arrange
        $form = $this->factory->create(DurationType::class, null, [
            'month' => true,
            'second' => true,
        ]);

        // Act
        $form->submit([
            'month' => '2',
            'hour' => '3',
            'minute' => '34',
            'second' => '12',
        ]);

        // Assert
        $view = $form->createView();

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals(new Second("5196852"), $form->getData()->getValue());
    }

    public function testSubmitNull(): void
    {
        // Arrange
        $form = $this->factory->create(DurationType::class);

        // Act
        $form->submit(null);

        // Assert
        $this->assertTrue($form->isSynchronized());
        $this->assertNull($form->getData());
    }

    public function testWithPreData(): void
    {
        // Arrange
        $form = $this->factory->create(DurationType::class, Duration::create(new Hour(3.2)));

        // Act
        $view = $form->createView();


        // Assert
        $this->assertSame([
            'year' => null,
            'month' => null,
            'day' => null,
            'hour' => '3',
            'minute' => '12',
            'second' => null,
        ], $view->vars['value']);
    }

    public function testWithNullPreData(): void
    {
        // Arrange
        $form = $this->factory->create(DurationType::class, null);

        // Act
        $view = $form->createView();

        // Assert
        $this->assertNull($view->vars['value']);
    }
}