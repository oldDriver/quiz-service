<?php declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\User;
use App\Tests\TestHelper;

class UserTest extends TestCase
{
    /**
     * @test
     * @group Unit
     * @group Entity
     */
    public function attributes(): void
    {
        $this->assertClassHasAttribute('id', User::class);
        $this->assertClassHasAttribute('roles', User::class);
    }

    /**
     * @test
     * @group Unit
     * @group Entity
     */
    public function defaultValues(): void
    {
        $id = TestHelper::getTestInt();
        $entity = new User($id);
        $this->assertEquals($id, $entity->getId());
        $this->assertEquals($id, $entity->getUsername());
        $this->assertIsArray($entity->getRoles());
        $this->assertCount(0, $entity->getRoles());
        $this->assertNull($entity->getPassword());
        $this->assertNull($entity->getSalt());
        $this->assertInstanceOf(User::class, $entity->eraseCredentials());
    }

    /**
     * @test
     * @group Unit
     * @group Entity
     * @dataProvider createFromPayloadCases
     */
    public function createFromPayload(array $payload, array $expect): void
    {
        $user = User::createFromPayload('', $payload);
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($expect['id'], $user->getId());
        $this->assertEquals($expect['roles'], $user->getRoles());
        $this->assertEquals($expect['id'], $user->getUsername());
        $this->assertNull($user->getPassword());
        $this->assertNull($user->getSalt());
    }

    public function createFromPayloadCases(): array
    {
        $id = TestHelper::getTestInt();
        return [
            [
                'payload' => [
                    'username' => $id,
                ],
                'expect' => [
                    'id' => $id,
                    'roles' => [],
                ]
            ],
            [
                'payload' => [
                    'username' => $id,
                    'roles' => ['ROLE_USER']
                ],
                'expect' => [
                    'id' => $id,
                    'roles' => ['ROLE_USER'],
                ]
            ],
            [
                'payload' => [
                    'username' => $id,
                    'roles' => ['ROLE_USER', 'ROLE_ALEX']
                ],
                'expect' => [
                    'id' => $id,
                    'roles' => ['ROLE_USER', 'ROLE_ALEX'],
                ]
            ],
            
        ];
    }

    /**
     * @test
     * @group Unit
     * @group Entity
     * @dataProvider isEqualToCases
     */
    public function isEqualTo(array $case, bool $expect): void
    {
        $user = new User($case['username'], $case['payload']);
        $this->assertEquals($expect, $user->isEqualTo($case['user']));
    }

    /**
     * @return array
     */
    public function isEqualToCases(): array
    {
        $id = TestHelper::getTestInt();
        $cases = [];
        $user = new User($id);
        $cases[] = [
            'case' => [
                'username' => $id,
                'payload' => [],
                'user' => $user
            ],
            'expect' => true
        ];
        $cases[] = [
            'case' => [
                'username' => TestHelper::getTestInt(),
                'payload' => [],
                'user' => $user
            ],
            'expect' => false
        ];
        $cases[] = [
            'case' => [
                'username' => $id,
                'payload' => [
                    'roles' => ['ROLE_TESTER']
                ],
                'user' => $user
            ],
            'expect' => false
        ];
        $tester = new User($id, ['roles' => ['ROLE_TESTER']]);
        $cases[] = [
            'case' => [
                'username' => $id,
                'payload' => [
                    'roles' => ['ROLE_TESTER']
                ],
                'user' => $tester
            ],
            'expect' => true
        ];
        return $cases;
    }
}
