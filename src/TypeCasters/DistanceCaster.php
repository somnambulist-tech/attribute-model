<?php declare(strict_types=1);

namespace Somnambulist\Components\AttributeModel\TypeCasters;

use Somnambulist\Components\Domain\Entities\Types\Measure\Distance;
use Somnambulist\Components\Domain\Entities\Types\Measure\DistanceUnit;
use Somnambulist\Components\AttributeModel\Contracts\AttributeCasterInterface;
use function in_array;

final class DistanceCaster implements AttributeCasterInterface
{
    public function __construct(
        private string $distAttribute = 'distance_value',
        private string $unitAttribute = 'distance_unit',
        private bool $remove = true
    ) {
    }

    public function types(): array
    {
        return ['distance', Distance::class];
    }

    public function supports(string $type): bool
    {
        return in_array($type, $this->types());
    }

    public function cast(array &$attributes, mixed $attribute, string $type): void
    {
        if (!isset($attributes[$this->distAttribute], $attributes[$this->unitAttribute])) {
            return;
        }

        $attributes[$attribute] = new Distance(
            (float)$attributes[$this->distAttribute],
            DistanceUnit::memberByValue($attributes[$this->unitAttribute])
        );

        if ($this->remove) {
            unset($attributes[$this->distAttribute], $attributes[$this->unitAttribute]);
        }
    }
}
