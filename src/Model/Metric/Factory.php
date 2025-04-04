<?php

namespace SoureCode\Bundle\Unit\Model\Metric;

use BcMath\Number;

/**
 * @template T of AbstractUnit
 *
 * @template-implements FactoryInterface<T>
 */
final readonly class Factory implements FactoryInterface
{
    public function __construct(
        /**
         * @var array<string, class-string<T>> $mapping
         */
        private array $mapping = [],
    ) {
    }

    /**
     * @psalm-return T
     */
    public function create(float|int|string|Number $value, Prefix $prefix): UnitInterface
    {
        if (!\array_key_exists($prefix->value, $this->mapping)) {
            throw new \InvalidArgumentException(\sprintf('Invalid prefix: %s', $prefix->value));
        }

        /**
         * @var class-string<T> $className
         */
        $className = $this->mapping[$prefix->value];

        return new $className($value);
    }
}
