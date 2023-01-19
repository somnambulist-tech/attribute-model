<?php declare(strict_types=1);

namespace Somnambulist\Components\AttributeModel\Tests;

use BadMethodCallException;
use PHPUnit\Framework\TestCase;
use Somnambulist\Components\AttributeModel\Tests\Support\Stubs\Models\User;
use Somnambulist\Components\Models\Types\DateTime\DateTime;

use function date;
use function password_hash;

/**
 * @group model
 */
class ModelTest extends TestCase
{
    public function testUnsupportedMethodRaisesExceptionOnModelBuilder()
    {
        $this->expectException(BadMethodCallException::class);

        $user = new User();
        $user->bob();
    }

    /**
     * @group attributes
     */
    public function testAttributeAccessors()
    {
        $user = new User([
            'id' => 1,
            'uuid' => '97c0c307-aac2-4486-ab22-45b5fed386c3',
            'email' => 'bob@example.com',
            'name' => 'bob',
            'is_active' => true,
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'created_at' => DateTime::now(),
            'updated_at' => DateTime::now(),
        ]);

        $this->assertEquals('97c0c307-aac2-4486-ab22-45b5fed386c3', (string)$user->uuid);
        $this->assertEquals('97c0c307-aac2-4486-ab22-45b5fed386c3', (string)$user->uuid());
        $this->assertEquals('bob@example.com', $user->email);
        $this->assertInstanceOf(DateTime::class, $user->createdAt());
        $this->assertInstanceOf(DateTime::class, $user->updated_at);
    }

    /**
     * @group attributes
     */
    public function testAttributeExistence()
    {
        $user = new User([
            'id' => 1,
            'uuid' => '97c0c307-aac2-4486-ab22-45b5fed386c3',
            'email' => 'bob@example.com',
            'name' => 'bob',
            'is_active' => true,
        ]);

        $this->assertTrue(isset($user->name));
        $this->assertTrue(isset($user->uuid));
        $this->assertFalse(isset($user->created_at));
    }

    /**
     * @group attributes
     * @group virtual-attributes
     */
    public function testAccessingVirtualAttributes()
    {
        $user = new User([
            'id' => 1,
            'uuid' => '97c0c307-aac2-4486-ab22-45b5fed386c3',
            'email' => 'bob@example.com',
            'name' => 'bob',
            'is_active' => true,
            'password' => password_hash('password', PASSWORD_DEFAULT),
            'created_at' => DateTime::now(),
            'updated_at' => DateTime::now(),
        ]);

        $this->assertEquals(date('l'), $user->registration_day);
        $this->assertEquals(date('l'), $user->registrationDay());

        $attrs = $user->getAttributes();

        $this->assertArrayHasKey('id', $attrs);
        $this->assertArrayHasKey('registration_day', $attrs);
        $this->assertArrayHasKey('registration_anniversary', $attrs);
        $this->assertArrayHasKey('1st_registration_anniversary', $attrs);
    }
}
