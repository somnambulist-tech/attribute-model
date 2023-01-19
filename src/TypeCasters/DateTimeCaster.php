<?php declare(strict_types=1);

namespace Somnambulist\Components\AttributeModel\TypeCasters;

use Somnambulist\Components\AttributeModel\Contracts\AttributeCasterInterface;
use Somnambulist\Components\AttributeModel\Exceptions\AttributeCasterException;
use Somnambulist\Components\Models\Types\DateTime\DateTime;

use function in_array;
use function is_null;

/**
 * Cast an attribute to a DateTime instance using a format.
 *
 * This single caster can be used multiple times with different formats to handle
 * casting dates, times, timezones or custom date formats e.g.: ATOM or W3C to
 * datetime objects.
 */
final class DateTimeCaster implements AttributeCasterInterface
{
    public function __construct(private string $format = 'Y-m-d H:i:s', private array $types = ['datetime'])
    {
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
        $val = $attributes[$attribute] ?? null;

        if (is_null($val)) {
            return;
        }

        if (false === $val = DateTime::createFromFormat($this->format, $val)) {
            throw AttributeCasterException::unableToCastAttributeToType($attribute, $type);
        }

        $attributes[$attribute] = $val;
    }
}
