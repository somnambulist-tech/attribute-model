<?php declare(strict_types=1);

namespace Somnambulist\Components\AttributeModel\Tests\Support\Stubs\Models;

use Somnambulist\Components\AttributeModel\AbstractModel;

/**
 * Class User
 *
 * @package    Somnambulist\Components\AttributeModel\Tests\Support\Stubs\Models
 * @subpackage Somnambulist\Components\AttributeModel\Tests\Support\Stubs\Models\User
 */
class User extends AbstractModel
{

    protected function getRegistrationDayAttribute()
    {
        return $this->created_at->format('l');
    }

    protected function getRegistrationAnniversaryAttribute()
    {
        return $this->created_at->format('dS F Y');
    }

    protected function get1stRegistrationAnniversaryAttribute()
    {
        return $this->created_at->format('dS F Y');
    }
}
