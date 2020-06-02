<?php
namespace App\Tests\Functional;

use App\Entity\User;

trait FunctionalTestTrait
{
    public function getJwtClient(?int $id = null, array $roles = ['ROLE_USER'])
    {
        static::bootKernel();
        if (empty($id)) {
            $id = random_int(1000, 90000);
        }
        $user = new User($id, ['roles' => $roles]);
        $jwtManager = parent::$container->get('lexik_jwt_authentication.jwt_manager');
        $token = $jwtManager->create($user);
        return static::createClient([], ['auth_bearer' => $token]);
    }
}