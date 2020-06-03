<?php declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\User;

class UserTest extends TestCase
{
    /**
     * @test
     * @group Unit
     * @group Entity
     */
    public function attributes()
    {
        $this->assertClassHasAttribute('id', User::class);
    }
}

