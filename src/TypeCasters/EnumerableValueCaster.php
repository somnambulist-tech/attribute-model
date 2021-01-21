<?php declare(strict_types=1);

namespace Somnambulist\Components\AttributeModel\TypeCasters;

use InvalidArgumentException;
use Somnambulist\Components\AttributeModel\Contracts\AttributeCasterInterface;
use Somnambulist\Components\Domain\Entities\AbstractEnumeration;
use function in_array;
use function is_a;

/**
 * Class EnumerableValueCaster
 *
 * Cast an enumerable using the value as the member, usually the constant value. If the
 * value is an int, then be sure to set the `$castAs` to `int` otherwise a string
 * comparison may be performed e.g.: if the value was loaded from PDO.
 *
 * @package    Somnambulist\Components\AttributeModel\TypeCasters
 * @subpackage Somnambulist\Components\AttributeModel\TypeCasters\EnumerableValueCaster
 */
final class EnumerableValueCaster implements AttributeCasterInterface
{

    private string $class;
    private string $preCastAs;
    private array  $types;

    public function __construct(string $class, array $types, string $preCastAs = 'string')
    {
        if (!is_a($class, AbstractEnumeration::class, $string = true)) {
            throw new InvalidArgumentException(sprintf('%s is not an instance of %s', $class, AbstractEnumeration::class));
        }
        if (!in_array($preCastAs, ['string', 'int'])) {
            throw new InvalidArgumentException(
                sprintf('Enumerable values can only be cast to "string" or "int", "%s" is not supported', $preCastAs)
            );
        }

        $this->class     = $class;
        $this->preCastAs = $preCastAs;
        $this->types     = $types;
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
        $value = $attributes[$attribute];

        if ('int' === $this->preCastAs) {
            $value = (int)$value;
        }

        $attributes[$attribute] = $this->class::memberByValue($value);
    }
}
