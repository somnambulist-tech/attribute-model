<?php declare(strict_types=1);

namespace Somnambulist\Components\AttributeModel\TypeCasters;

use InvalidArgumentException;
use Somnambulist\Components\AttributeModel\Contracts\AttributeCasterInterface;
use Somnambulist\Components\Models\AbstractEnumeration;

use function in_array;
use function is_a;

/**
 * Cast an enumerable using the value as the member, usually the constant value.
 *
 * If the value is an int, then be sure to set the `$castAs` to `int` otherwise a string
 * comparison may be performed e.g.: if the value was loaded from PDO.
 */
final class EnumerableValueCaster implements AttributeCasterInterface
{
    public function __construct(private string $class, private array $types, private string $preCastAs = 'string')
    {
        if (!is_a($class, AbstractEnumeration::class, true)) {
            throw new InvalidArgumentException(sprintf('%s is not an instance of %s', $class, AbstractEnumeration::class));
        }
        if (!in_array($preCastAs, ['string', 'int'])) {
            throw new InvalidArgumentException(
                sprintf('Enumerable values can only be cast to "string" or "int", "%s" is not supported', $preCastAs)
            );
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
        $value = $attributes[$attribute];

        if ('int' === $this->preCastAs) {
            $value = (int)$value;
        }

        $attributes[$attribute] = $this->class::memberByValue($value);
    }
}
