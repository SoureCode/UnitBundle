<?php

namespace SoureCode\Bundle\Unit\Tests;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Nyholm\BundleTest\TestKernel;
use SoureCode\Bundle\Unit\SoureCodeUnitBundle;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

class AbstractKernelTestCase extends KernelTestCase
{
    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }

    protected static function createKernel(array $options = []): KernelInterface
    {
        /**
         * @var TestKernel $kernel
         */
        $kernel = parent::createKernel($options);
        $kernel->setTestProjectDir(__DIR__ . '/app');
        $kernel->addTestBundle(DoctrineBundle::class);
        $kernel->addTestBundle(SoureCodeUnitBundle::class);
        $kernel->addTestConfig(__DIR__ . '/app/config/config.yaml');
        $kernel->addTestConfig(__DIR__ . '/app/config/doctrine.yaml');
        $kernel->handleOptions($options);

        return $kernel;
    }
}