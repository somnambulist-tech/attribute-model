# Attribute Model

[![GitHub Actions Build Status](https://img.shields.io/github/actions/workflow/status/somnambulist-tech/attribute-model/tests.yml?logo=github&branch=main)](https://github.com/somnambulist-tech/attribute-model/actions?query=workflow%3Atests)
[![Issues](https://img.shields.io/github/issues/somnambulist-tech/attribute-model?logo=github)](https://github.com/somnambulist-tech/attribute-model/issues)
[![License](https://img.shields.io/github/license/somnambulist-tech/attribute-model?logo=github)](https://github.com/somnambulist-tech/attribute-model/blob/master/LICENSE)
[![PHP Version](https://img.shields.io/packagist/php-v/somnambulist/attribute-model?logo=php&logoColor=white)](https://packagist.org/packages/somnambulist/attribute-model)
[![Current Version](https://img.shields.io/packagist/v/somnambulist/attribute-model?logo=packagist&logoColor=white)](https://packagist.org/packages/somnambulist/attribute-model)

A sort-of-kind-of ActiveModel type'ish base for Models that rely on an array of attributes.
Includes a type-casting sub-system for casting attributes to values. Extracted from read-models.

The focus is for creating read-only Model representations for use in presentation layers.
This library is used by [somnambulist/read-models](https://github.com/somnambulist-tech/read-models) and
[api-client](https://github.com/somnambulist-tech/api-client).

## Requirements

 * PHP 8.0+
 * pragmarx/ia-str

## Installation

Install using composer, or checkout / pull the files from github.com.

 * composer require somnambulist/attribute-model

## Usage

Extend to a model e.g. User; or implement into a base model and add extra functionality.
Use attribute casting if needed before passing attributes into the model.

```php
<?php
use Somnambulist\Components\AttributeModel\AbstractModel;
use Somnambulist\Components\AttributeModel\AttributeCaster;
use Somnambulist\Components\AttributeModel\TypeCasters\AreaCaster;
use Somnambulist\Components\AttributeModel\TypeCasters\MoneyCaster;

class User extends AbstractModel
{

}

$caster = new AttributeCaster([
    new AreaCaster(),
    new MoneyCaster(),
]);
$attrs = [];
$user = new User($caster->cast($attrs, ['area' => 'area', 'money' => 'money',]));
```

### Built-in Casters

The following casters are built-in and are largely configurable by type or attribute name(s):

| Caster | Output | Comments |
|---|---|---|
| AreaCaster | Somnambulist\Components\Domain\Entities\Types\Measure\Area | convert a value + unit to an Area value object |
| CoordinateCaster | Somnambulist\Components\Domain\Entities\Types\Geography\Coordinate | convert lat/long/srid strings to value object |
| DateTimeCaster | Somnambulist\Components\Domain\Entities\Types\DateTime\DateTime | convert a date/time in a format to a DateTime object |
| DistanceCaster | Somnambulist\Components\Domain\Entities\Types\Measure\Distance | convert a value + unit to a Distance value object |
| EnumerableKeyCaster | Somnambulist\Components\Domain\Entities\AbstractEnumeration | returns instantiated enumeration object using the member key; may also be a multiton |
| EnumerableValueCaster | Somnambulist\Components\Domain\Entities\AbstractEnumeration | returns instantiated enumeration object using the member value |
| JsonCollectionCaster | Somnambulist\Collection\MutableCollection | decodes a JSON string into a collection object |
| MoneyCaster | Somnambulist\Components\Domain\Entities\Types\Money\Money | convert a value + ISO currency to value object |
| SimpleValueObjectCaster | Somnambulist\Components\Domain\Entities\AbstractValueObject | creates value-objects from a single string value e.g. EmailAddress |

Many of the casters accept alternative attribute names for matching and type overrides. Suitable
defaults are provided where appropriate (e.g.: json, json_array, json_collection).

More casters can be added by implementing the interface and attaching to the `AttributeCaster`.

An existing caster can be re-used on another type by calling `$caster->extend(<type>, [new, types, here])`.
The configuration of the caster cannot be changed; it adds extra type keys that the caster will
respond to.

## Tests

PHPUnit 9+ is used for testing. Run tests via `vendor/bin/phpunit`.
