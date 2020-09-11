<?php declare(strict_types=1);

namespace Somnambulist\Components\AttributeModel\Tests;

use Eloquent\Enumeration\Exception\UndefinedMemberException;
use PHPUnit\Framework\TestCase;
use Somnambulist\Collection\Contracts\Collection;
use Somnambulist\Components\AttributeModel\AttributeCaster;
use Somnambulist\Components\AttributeModel\Contracts\AttributeCasterInterface;
use Somnambulist\Components\AttributeModel\Exceptions\AttributeCasterException;
use Somnambulist\Components\AttributeModel\TypeCasters;
use Somnambulist\Domain\Entities\Types\DateTime\DateTime;
use Somnambulist\Domain\Entities\Types\Geography\Country;
use Somnambulist\Domain\Entities\Types\Geography\Srid;
use Somnambulist\Domain\Entities\Types\Identity\EmailAddress;
use Somnambulist\Domain\Entities\Types\Identity\ExternalIdentity;
use Somnambulist\Domain\Entities\Types\Measure\Area;
use Somnambulist\Domain\Entities\Types\Measure\Distance;
use Somnambulist\Domain\Entities\Types\Money\Money;
use Somnambulist\Domain\Entities\Types\PhoneNumber;

/**
 * Class AttributeCasterTest
 *
 * @package    Somnambulist\Components\AttributeModel\Tests
 * @subpackage Somnambulist\Components\AttributeModel\Tests\AttributeCasterTest
 *
 * @group      attribute-caster
 */
class AttributeCasterTest extends TestCase
{

    private ?AttributeCaster $caster = null;

    protected function setUp(): void
    {
        $this->caster = new AttributeCaster([
            new TypeCasters\DateTimeCaster(),
            new TypeCasters\DateTimeCaster('!Y-m-d', ['date']),
            new TypeCasters\DateTimeCaster('!H:i:s', ['time']),

            new TypeCasters\AreaCaster(),
            new TypeCasters\CoordinateCaster(),
            new TypeCasters\DistanceCaster(),
            new TypeCasters\ExternalIdentityCaster(),
            new TypeCasters\MoneyCaster('total_amount', 'total_currency'),

            new TypeCasters\EnumerableValueCaster(Srid::class, ['srid']),
            new TypeCasters\EnumerableKeyCaster(Country::class, ['country']),

            new TypeCasters\JsonCollectionCaster(),

            new TypeCasters\SimpleValueObjectCaster(EmailAddress::class, ['email_address']),
            new TypeCasters\SimpleValueObjectCaster(PhoneNumber::class, ['phone_number']),
        ]);
    }

    public function testAdd()
    {
        $this->caster->add(new TypeCasters\DateTimeCaster(), ['another_datetime']);

        $this->assertTrue($this->caster->has('another_datetime'));
    }

    public function testHas()
    {
        $this->assertFalse($this->caster->has('bob'));
        $this->assertTrue($this->caster->has('datetime'));
    }

    public function testCast()
    {
        $casts = [
            'created_at' => 'datetime',
            'country'    => 'country',
            'srid'       => 'srid',
            'area'       => 'area',
            'distance'   => 'distance',
            'properties' => 'json',
            'total'      => 'money',
            'service_id' => 'external_id',
            'phone'      => 'phone_number',
            'email'      => 'email_address',
        ];

        $attributes = [
            'created_at'     => '2020-09-07 12:21:35',
            'country'        => 'CAN',
            'srid'           => 4326,
            'area_value'     => '23.45',
            'area_unit'      => 'sq_m',
            'distance_value' => '345.32',
            'distance_unit'  => 'km',
            'properties'     => '{"this":"that","array":[1,3,5,1234]}',
            'total_amount'   => '123.45',
            'total_currency' => 'CAD',
            'provider'       => 'foobarbaz',
            'identity'       => '94dff70c-9f0a-4695-9352-5018888ea529',
            'phone'          => '+1234567890',
            'email'          => 'foo@example.com',
        ];

        $casted = $this->caster->cast($attributes, $casts);

        // direct conversions
        $this->assertInstanceOf(DateTime::class, $casted['created_at']);
        $this->assertInstanceOf(Country::class, $casted['country']);
        $this->assertInstanceOf(Srid::class, $casted['srid']);
        $this->assertInstanceOf(Collection::class, $casted['properties']);
        $this->assertInstanceOf(PhoneNumber::class, $casted['phone']);
        $this->assertInstanceOf(EmailAddress::class, $casted['email']);

        // JSON decoded
        $this->assertEquals('that', $casted['properties']->get('this'));
        $this->assertIsIterable($casted['properties']->get('array'));

        // bound + removed
        $this->assertInstanceOf(Distance::class, $casted['distance']);
        $this->assertArrayNotHasKey('distance_value', $casted);
        $this->assertArrayNotHasKey('distance_unit', $casted);
        $this->assertInstanceOf(Area::class, $casted['area']);
        $this->assertArrayNotHasKey('area_value', $casted);
        $this->assertArrayNotHasKey('area_unit', $casted);
        $this->assertInstanceOf(ExternalIdentity::class, $casted['service_id']);
        $this->assertArrayNotHasKey('provider', $casted);
        $this->assertArrayNotHasKey('identity', $casted);
        $this->assertInstanceOf(Money::class, $casted['total']);
        $this->assertArrayNotHasKey('total_amount', $casted);
        $this->assertArrayNotHasKey('total_currency', $casted);
    }

    public function testNullCast()
    {
        $casts = [
            'created_at' => 'datetime',
            'phone'      => 'phone_number',
            'email'      => 'email_address',
            'properties' => 'json',
        ];

        $attributes = [
            'created_at' => null,
            'phone'      => null,
            'email'      => null,
            'properties' => null,
        ];

        $casted = $this->caster->cast($attributes, $casts);

        // direct conversions
        $this->assertNull($casted['created_at']);
        $this->assertNull($casted['phone']);
        $this->assertNull($casted['email']);
        $this->assertInstanceOf(Collection::class, $casted['properties']);
    }

    public function testCastingEnumValuesRaisesExceptions()
    {
        $casts = [
            'srid' => 'srid',
        ];

        $attributes = [
            'srid' => null,
        ];

        $this->expectException(UndefinedMemberException::class);

        $this->caster->cast($attributes, $casts);
    }

    public function testCastingEnumKeysRaisesExceptions()
    {
        $casts = [
            'country' => 'country',
        ];

        $attributes = [
            'country' => null,
        ];

        $this->expectException(UndefinedMemberException::class);

        $this->caster->cast($attributes, $casts);
    }

    public function testCastingNullExternalIdentitiesReturnsNull()
    {
        $casts = [
            'product_id' => 'external_id',
        ];

        $attributes = [

        ];

        $casted = $this->caster->cast($attributes, $casts);

        $this->assertArrayNotHasKey('product_id', $casted);
    }

    public function testCastingNullAreaReturnsNull()
    {
        $casts = [
            'area' => 'area',
        ];

        $attributes = [

        ];

        $casted = $this->caster->cast($attributes, $casts);

        $this->assertArrayNotHasKey('area', $casted);
    }

    public function testFor()
    {
        $this->assertInstanceOf(AttributeCasterInterface::class, $this->caster->for('datetime'));
    }

    public function testForRaisesExceptionIfNotRegistered()
    {
        $this->expectException(AttributeCasterException::class);

        $this->caster->for('foobar');
    }

    public function testExtend()
    {
        $this->caster->extend('datetime', ['datetime_tz', 'my_datetime']);

        $this->assertTrue($this->caster->has('datetime_tz'));
        $this->assertTrue($this->caster->has('my_datetime'));

        $caster = $this->caster->for('datetime');

        $this->assertSame($caster, $this->caster->for('datetime_tz'));
        $this->assertSame($caster, $this->caster->for('my_datetime'));
    }
}
