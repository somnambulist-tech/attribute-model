<?php declare(strict_types=1);

namespace Somnambulist\Components\AttributeModel\TypeCasters;

use Somnambulist\Components\AttributeModel\Contracts\AttributeCasterInterface;
use Somnambulist\Components\AttributeModel\Exceptions\AttributeCasterException;
use Somnambulist\Domain\Entities\Types\DateTime\DateTime;
use function in_array;
use function is_null;

/**
 * Class DateTimeCaster
 *
 * Cast an attribute to a DateTime instance using a format. This single caster
 * can be used multiple times with different formats to handle casting dates,
 * times, timezones or custom date formats e.g.: ATOM or W3C to date time objects.
 *
 * @package    Somnambulist\Components\AttributeModel\TypeCasters
 * @subpackage Somnambulist\Components\AttributeModel\TypeCasters\DateTimeCaster
 */
final class DateTimeCaster implements AttributeCasterInterface
{

    private string $format;
    private array $types;

    public function __construct(string $format = 'Y-m-d H:i:s', array $types = ['datetime'])
    {
        $this->format = $format;
        $this->types  = $types;
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
