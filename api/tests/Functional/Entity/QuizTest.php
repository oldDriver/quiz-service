<?php
namespace App\Tests\Functional\Entity;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\BaseDatabaseTrait;
use Symfony\Component\HttpFoundation\Request;
use App\Tests\Functional\FunctionalTestTrait;
use Symfony\Component\HttpFoundation\Response;

class QuizTest extends ApiTestCase
{
    use BaseDatabaseTrait;
    use FunctionalTestTrait;
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
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        $client = $this->getJwtClient();
        $client->request(Request::METHOD_GET, $this->testUrl);
        $this->assertResponseIsSuccessful();
    }
}