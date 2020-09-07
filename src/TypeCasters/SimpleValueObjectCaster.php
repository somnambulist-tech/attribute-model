<?php declare(strict_types=1);

namespace Somnambulist\Components\AttributeModel\TypeCasters;

use InvalidArgumentException;
use Somnambulist\Components\AttributeModel\Contracts\AttributeCasterInterface;
use Somnambulist\Domain\Entities\AbstractValueObject;
use function in_array;
use function is_a;
use function sprintf;

/**
 * Class SimpleValueObjectCaster
 *
 * Cast an attribute to a single argument value-object e.g.: EmailAddress, or PhoneNumber.
 * Can be used when Doctrine types are not available.
 *
 * @package    Somnambulist\Components\AttributeModel\TypeCasters
 * @subpackage Somnambulist\Components\AttributeModel\TypeCasters\SimpleValueObjectCaster
 */
final class SimpleValueObjectCaster implements AttributeCasterInterface
{

    private string $class;
    private array $types;

    public function __construct(string $class, array $types)
    {
        if (!is_a($class, AbstractValueObject::class, $string = true)) {
            throw new InvalidArgumentException(sprintf('%s is not an instance of %s', $class, AbstractValueObject::class));
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
        $attributes[$attribute] = new $this->class($attributes[$attribute]);
    }
}
