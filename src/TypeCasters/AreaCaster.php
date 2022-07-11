<?php declare(strict_types=1);

namespace Somnambulist\Components\AttributeModel\TypeCasters;

use Somnambulist\Components\Domain\Entities\Types\Measure\Area;
use Somnambulist\Components\Domain\Entities\Types\Measure\AreaUnit;
use Somnambulist\Components\AttributeModel\Contracts\AttributeCasterInterface;
use function in_array;

final class AreaCaster implements AttributeCasterInterface
{
    public function __construct(
        private string $areaAttribute = 'area_value',
        private string $unitAttribute = 'area_unit',
        private bool $remove = true
    ) {
    }

    public function types(): array
    {
        return ['area', Area::class];
    }

    public function supports(string $type): bool
    {
        return in_array($type, $this->types());
    }

    public function cast(array &$attributes, mixed $attribute, string $type): void
    {
        if (!isset($attributes[$this->areaAttribute], $attributes[$this->unitAttribute])) {
            return;
        }

        $attributes[$attribute] = new Area(
            (float)$attributes[$this->areaAttribute],
            AreaUnit::memberByValue($attributes[$this->unitAttribute])
        );

        if ($this->remove) {
            unset($attributes[$this->areaAttribute], $attributes[$this->unitAttribute]);
        }
    }
}
