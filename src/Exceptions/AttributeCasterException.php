<?php declare(strict_types=1);

namespace Somnambulist\Components\AttributeModel\Exceptions;

use Exception;

use function sprintf;

class AttributeCasterException extends Exception
{
    public static function missingTypeFor(string $type): self
    {
        return new self(sprintf('Missing type caster for "%s"', $type));
    }

    public static function unableToCastAttributeToType(string $attribute, string $type): self
    {
        return new self(sprintf('Failed to convert attribute "%s" to type "%s"', $attribute, $type));
    }
}
