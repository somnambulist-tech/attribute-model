<?php declare(strict_types=1);

namespace Somnambulist\Components\AttributeModel\TypeCasters;

use BackedEnum;
use InvalidArgumentException;
use Somnambulist\Components\AttributeModel\Contracts\AttributeCasterInterface;
use function in_array;
use function is_a;
use function sprintf;

/**
 * Cast to a native PHP enum
 */
final class EnumCaster implements AttributeCasterInterface
{
    public function __construct(private string $class, private array $types)
    {
        if (!is_a($class, BackedEnum::class, true)) {
            throw new InvalidArgumentException(sprintf('%s is not an int or string backed enum', $class));
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
        $attributes[$attribute] = $this->class::from($attributes[$attribute]);
    }
}
