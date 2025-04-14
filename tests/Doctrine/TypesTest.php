<?php

namespace SoureCode\Bundle\Unit\Tests\Doctrine;

use App\Entity\Goal;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\Attributes\DataProvider;
use SoureCode\Bundle\Unit\Model\Distance;
use SoureCode\Bundle\Unit\Model\Duration;
use SoureCode\Bundle\Unit\Model\Length\Meter;
use SoureCode\Bundle\Unit\Model\Time\Hour;
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

    #[DataProvider('distanceTypeDataProvider')]
    public function testDistanceType(string $input, string $expected): void
    {
        // Arrange
        $goal = new Goal();
        $goal->setTargetDistance(Distance::create(new Meter($input)));

        // Act & Assert
        $this->entityManager->persist($goal);
        $this->entityManager->flush();

        // Assert
        $id = $goal->getId();
        $this->entityManager->clear();
        $goal = $this->repository->find($id);

        $this->assertInstanceOf(Goal::class, $goal);
        $this->assertEquals($expected, $goal->getTargetDistance()->format());
    }

    public static function distanceTypeDataProvider(): array
    {
        return [
            ['0.0', '0m'],
            ['1.0', '1m'],
            ['-1.0', '-1m'],
            ['123.456', '1.23456hm'],
            ['-987.654', '-9.87654hm'],
            ['0.00001', '10μm'],
            ['-0.00001', '-10μm'],
            ['1.23456789', '1.23456789m'],
            ['999999.9999', '999.9999999km'],
            ['-999999.9999', '-999.9999999km'],
            ['3.1415926535', '3.1415926535m'],
            ['2.7182818284', '2.7182818284m'],
            ['123456789.123', '123456.789123km'],

            ['0', '0m'],
            ['+123.45', '1.2345hm'],
            ['-0.0', '0m'],
            ['0001.000', '1m'],
            ['123.', '1.23hm'],
            ['.456', '4.56dm'],
            ['0.000000001', '1nm'],
            ['1e-5', '10μm'],
            ['1e5', '100km'],
        ];
    }

    #[DataProvider('durationTypeDataProvider')]
    public function testDurationType(string $input, string $expected): void
    {
        // Arrange
        $goal = new Goal();
        $goal->setTargetDuration(Duration::create(new Hour($input)));

        // Act & Assert
        $this->entityManager->persist($goal);
        $this->entityManager->flush();

        // Assert
        $id = $goal->getId();
        $this->entityManager->clear();
        $goal = $this->repository->find($id);

        $this->assertInstanceOf(Goal::class, $goal);
        $this->assertEquals($expected, $goal->getTargetDuration()->totalSeconds()->getValue());
    }

    public static function durationTypeDataProvider(): array
    {
        return [
            ["0", "0"],
            ["1", "3600"],
            ["2", "7200"],
            ["5", "18000"],
            ["10", "36000"],
            ["24", "86400"],
            ["0.5", "1800"],
            ["1.25", "4500"],
            ["1.5", "5400"],
            ["2.75", "9900"],
            ["100", "360000"],
            ["0.001", "3.6"],
            // @note: just works because the scale is so high. >:D (never do that)
            ["0.000277777777777777777778", "1"],
            ["0.0000001", "0.00036"],
            ["1.0000001", "3600.00036"],
            ["0.999999", "3599.9964"],
            ["1.000001", "3600.0036"],
            ["1e1", "36000"],
            ["2.5e-1", "900"],
            ["0.0000000001", "0.00000036"],
            ["10000", "36000000"],
        ];
    }
}
