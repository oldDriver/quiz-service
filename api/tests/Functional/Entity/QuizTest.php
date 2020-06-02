<?php
namespace App\Tests\Functiona\Entity;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\BaseDatabaseTrait;
use Symfony\Component\HttpFoundation\Request;

class QuizTest extends ApiTestCase
{
    use BaseDatabaseTrait;
    private string $testUrl = '/quizzes';
    public static function setUpBeforeClass(): void
    {
        static::bootKernel();
        static::populateDatabase();
    }
    
    /**
     * @test
     * @group Functional
     * @group Entity
     * @group Quiz
     */
    public function collection()
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, $this->testUrl);
        $this->assertResponseIsSuccessful();
    }
}