<?php
namespace App\Tests\Functional;

use App\Security\User;
use App\Entity\Quiz;

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

    public function getJwtEditorClient(?int $id = null)
    {
        return $this->getJwtClient($id, ['ROLE_EDITOR']);
    }

    public function getJwtAdminClient(?int $id = null)
    {
        return $this->getJwtClient($id, ['ROLE_ADMIN']);
    }
    
    public function getTestQuiz(): array
    {
        return [
            'name' => 'Test Crud',
            'description' => 'Test description for test quiz'
        ];
    }

    public function getQuestionCreateArray(): array
    {
        static::bootKernel();
        $quizIri = static::findIriBy(Quiz::class, ['slug' => 'quiz-for-developers']);
        return [
            'quiz' => $quizIri,
            'question' => 'Are you here?'
        ];
    }
}