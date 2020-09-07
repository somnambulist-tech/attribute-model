<?php declare(strict_types=1);

namespace Somnambulist\Components\AttributeModel\TypeCasters;

use InvalidArgumentException;
use Somnambulist\Components\AttributeModel\Contracts\AttributeCasterInterface;
use Somnambulist\Domain\Entities\AbstractEnumeration;
use function in_array;
use function is_a;

/**
 * Class EnumerableValueCaster
 *
 * Cast an enumerable using the value as the member, usually the constant value.
 *
 * @package    Somnambulist\Components\AttributeModel\TypeCasters
 * @subpackage Somnambulist\Components\AttributeModel\TypeCasters\EnumerableValueCaster
 */
final class EnumerableValueCaster implements AttributeCasterInterface
{

    private string $class;
    private array  $types;

    public function __construct(string $class, array $types)
    {
        if (!is_a($class, AbstractEnumeration::class, $string = true)) {
            throw new InvalidArgumentException(sprintf('%s is not an instance of %s', $class, AbstractEnumeration::class));
        }

        $this->class = $class;
        $this->types = $types;
    }

    public function types(): array
    {
        return $this->types;
    }

    public function supports(string $type): bool
    {
        return in_array($type, $this->types());
    }

    public function cast(array &$attributes, $attribute, string $type): void
    {
        $attributes[$attribute] = $this->class::memberByValue($attributes[$attribute]);
    }
}
