<?php declare(strict_types=1);

namespace Somnambulist\Components\AttributeModel\TypeCasters;

use Somnambulist\Domain\Entities\Types\Measure\Area;
use Somnambulist\Domain\Entities\Types\Measure\AreaUnit;
use Somnambulist\Components\AttributeModel\Contracts\AttributeCasterInterface;
use function in_array;

/**
 * Class AreaCaster
 *
 * @package    Somnambulist\Components\AttributeModel\TypeCasters
 * @subpackage Somnambulist\Components\AttributeModel\TypeCasters\AreaCaster
 */
final class AreaCaster implements AttributeCasterInterface
{

    private string $areaAttribute;
    private string $unitAttribute;
    private bool $remove;

    public function __construct(string $areaAttribute = 'area_value', string $unitAttribute = 'area_unit', bool $remove = true)
    {
        $this->areaAttribute = $areaAttribute;
        $this->unitAttribute = $unitAttribute;
        $this->remove        = $remove;
    }

    public function types(): array
    {
        return ['area', Area::class];
    }

    public function supports(string $type): bool
    {
        return in_array($type, $this->types());
    }

    public function cast(array &$attributes, $attribute, string $type): void
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
