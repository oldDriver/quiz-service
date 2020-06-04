<?php
namespace App\Tests\Functional\Entity;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\BaseDatabaseTrait;
use Symfony\Component\HttpFoundation\Request;
use App\Tests\Functional\FunctionalTestTrait;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Quiz;

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
        // anonymous
        $client = static::createClient();
        $client->request(Request::METHOD_GET, $this->testUrl);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertCount(3, $client->getResponse()->toArray()['hydra:member']);
        $this->assertMatchesResourceCollectionJsonSchema(Quiz::class);
        // user
        $client = $this->getJwtClient();
        $client->request(Request::METHOD_GET, $this->testUrl);
        $this->assertResponseIsSuccessful();
    }
}