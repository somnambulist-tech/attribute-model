<?php declare(strict_types=1);

namespace Somnambulist\Components\AttributeModel\TypeCasters;

use InvalidArgumentException;
use Somnambulist\Components\AttributeModel\Contracts\AttributeCasterInterface;
use Somnambulist\Components\Domain\Entities\AbstractValueObject;
use function in_array;
use function is_a;
use function sprintf;

/**
 * Cast an attribute to a single argument value-object e.g.: EmailAddress, or PhoneNumber.
 * Can be used when Doctrine types are not available.
 */
final class SimpleValueObjectCaster implements AttributeCasterInterface
{
    public function __construct(private string $class, private array $types)
    {
        if (!is_a($class, AbstractValueObject::class, true)) {
            throw new InvalidArgumentException(sprintf('%s is not an instance of %s', $class, AbstractValueObject::class));
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
        $value = $attributes[$attribute] ?? null;

        if (is_null($value)) {
            return;
        }

        $attributes[$attribute] = new $this->class($attributes[$attribute]);
    }
}
