<?php declare(strict_types=1);

namespace Somnambulist\Components\AttributeModel\TypeCasters;

use Somnambulist\Components\AttributeModel\Contracts\AttributeCasterInterface;
use Somnambulist\Components\Models\Types\Geography\Coordinate;
use Somnambulist\Components\Models\Types\Geography\Srid;

use function in_array;

final class CoordinateCaster implements AttributeCasterInterface
{
    public function __construct(
        private string $latAttribute = 'latitude',
        private string $lngAttribute = 'longitude',
        private string $sridAttribute = 'srid',
        private bool $remove = true
    ) {
    }

    public function types(): array
    {
        return ['coordinate', Coordinate::class];
    }

    public function supports(string $type): bool
    {
        return in_array($type, $this->types());
    }

    public function cast(array &$attributes, mixed $attribute, string $type): void
    {
        if (!isset($attributes[$this->latAttribute], $attributes[$this->lngAttribute], $attributes[$this->sridAttribute])) {
            return;
        }

        $attributes[$attribute] = new Coordinate(
            $attributes[$this->latAttribute], $attributes[$this->lngAttribute], Srid::memberByValue($attributes[$this->sridAttribute])
        );

        if ($this->remove) {
            unset($attributes[$this->lngAttribute], $attributes[$this->latAttribute], $attributes[$this->sridAttribute]);
        }
    }
}
