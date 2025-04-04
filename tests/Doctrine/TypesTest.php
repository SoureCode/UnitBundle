<?php

namespace SoureCode\Bundle\Unit\Tests\Doctrine;

use App\Entity\Goal;
use Doctrine\ORM\EntityRepository;
use SoureCode\Bundle\Unit\Model\Metric\Length\Meter;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\Attributes\DataProvider;
use SoureCode\Bundle\Unit\Tests\AbstractKernelTestCase;

final class TypesTest extends AbstractKernelTestCase
{
    private EntityRepository $repository;

    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $container = self::getContainer();
        $this->entityManager = $container->get(EntityManagerInterface::class);
        $schemaTool = new SchemaTool($this->entityManager);

        $schemaTool->createSchema([
            $this->entityManager->getClassMetadata(Goal::class)
        ]);

        $this->repository = $this->entityManager->getRepository(Goal::class);
    }

    #[DataProvider('lengthUnitDataProvider')]
    public function testLengthUnit(string $input, string $expected): void
    {
        // Arrange
        $unit = new Meter($input);
        $goal = new Goal();
        $goal->setTarget($unit);

        // Act & Assert
        $this->entityManager->persist($goal);
        $this->entityManager->flush();

        // Assert
        $id = $goal->getId();
        $this->entityManager->clear();
        $goal = $this->repository->find($id);

        $this->assertInstanceOf(Goal::class, $goal);
        $this->assertEquals($expected, $goal->getTarget()->format());
    }

    public static function lengthUnitDataProvider(): array
    {
        return [
            ['0.0', '0m'],
            ['1.0', '1m'],
            ['-1.0', '-1m'],
            ['123.456', '123.456m'],
            ['-987.654', '-987.654m'],
            ['0.00001', '10μm'],
            ['-0.00001', '-10μm'],
            ['1.23456789', '1.23456789m'],
            ['999999.9999', '999999.9999m'],
            ['-999999.9999', '-999999.9999m'],
            ['3.1415926535', '3.1415926535m'],
            ['2.7182818284', '2.7182818284m'],
            ['123456789.123', '123456789.123m'],

            ['0', '0m'],
            ['+123.45', '123.45m'],
            ['-0.0', '0m'],
            ['0001.000', '1m'],
            ['123.', '123m'],
            ['.456', '456mm'],
            ['0.000000001', '1nm'],
            ['1e-5', '10μm'],
            ['1e5', '100km'],
        ];
    }
}
