<?php declare(strict_types=1);

namespace Somnambulist\Components\AttributeModel\TypeCasters;

use InvalidArgumentException;
use Somnambulist\Components\AttributeModel\Contracts\AttributeCasterInterface;
use Somnambulist\Components\Models\AbstractEnumeration;
use Somnambulist\Components\Models\AbstractMultiton;

use function in_array;
use function is_a;

/**
 * Cast to an enumerable by the member key; usually the constant name.
 */
final class EnumerableKeyCaster implements AttributeCasterInterface
{
    public function __construct(private string $class, private array $types)
    {
        if (!is_a($class, AbstractEnumeration::class, true) && !is_a($class, AbstractMultiton::class, true)) {
            throw new InvalidArgumentException(sprintf('%s is not an instance of %s or %s', $class, AbstractEnumeration::class, AbstractMultiton::class));
        }
    }

    public function types(): array
    {
        return $this->types;
    }

    public function supports(string $type): bool
    {
        return in_array($type, $this->types());
    }

    public function cast(array &$attributes, mixed $attribute, string $type): void
    {
        $attributes[$attribute] = $this->class::memberByKey($attributes[$attribute]);
    }
}
