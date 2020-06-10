<?php declare(strict_types=1);

namespace App\Tests\Functional\Entity;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\BaseDatabaseTrait;
use Symfony\Component\HttpFoundation\Request;
use App\Tests\Functional\FunctionalTestTrait;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Quiz;
use App\Entity\Question;

class QuizCollectionTest extends ApiTestCase
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
     * @large
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
        $this->assertCount(4, $client->getResponse()->toArray()['hydra:member']);
        $this->assertMatchesResourceCollectionJsonSchema(Quiz::class);
        // user
        $client = $this->getJwtClient();
        $client->request(Request::METHOD_GET, $this->testUrl);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertCount(4, $client->getResponse()->toArray()['hydra:member']);
        $this->assertMatchesResourceCollectionJsonSchema(Quiz::class);
        // editor
        $client = $this->getJwtClient(null, ['ROLE_EDITOR']);
        $client->request(Request::METHOD_GET, $this->testUrl);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertCount(4, $client->getResponse()->toArray()['hydra:member']);
        $this->assertMatchesResourceCollectionJsonSchema(Quiz::class);
        // admin
        $client = $this->getJwtClient(null, ['ROLE_ADMIN']);
        $client->request(Request::METHOD_GET, $this->testUrl);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertCount(4, $client->getResponse()->toArray()['hydra:member']);
        $this->assertMatchesResourceCollectionJsonSchema(Quiz::class);
    }

    /**
     * @test
     * @large
     * @group Functional
     * @group Entity
     * @group Quiz
     */
    public function search()
    {
        // common phrase
        $client = static::createClient();
        $client->request(Request::METHOD_GET, $this->testUrl.'?slug=quiz');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertCount(3, $client->getResponse()->toArray()['hydra:member']);
        $this->assertMatchesResourceCollectionJsonSchema(Quiz::class);
        // more strict criteria
        $client = static::createClient();
        $client->request(Request::METHOD_GET, $this->testUrl.'?slug=developers');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertCount(1, $client->getResponse()->toArray()['hydra:member']);
        $this->assertMatchesResourceCollectionJsonSchema(Quiz::class);
        // dummy criteria
        $client = static::createClient();
        $client->request(Request::METHOD_GET, $this->testUrl.'?slug=abracadabra');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertCount(0, $client->getResponse()->toArray()['hydra:member']);
    }

    /**
     * @test
     * @large
     * @group Functional
     * @group Entity
     * @group Quiz
     */
    public function details()
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, $this->testUrl.'?slug=developers');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertCount(1, $client->getResponse()->toArray()['hydra:member']);
        $this->assertMatchesResourceCollectionJsonSchema(Quiz::class);
        $content = json_decode($client->getResponse()->getContent(), true);
        $quizUrl = $content['hydra:member'][0]['@id'];
        // amonymous
        $client->request(Request::METHOD_GET, $quizUrl);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesResourceItemJsonSchema(Quiz::class);
    }

    /**
     * @test
     * @large
     * @group Functional
     * @group Quiz
     */
    public function questions()
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, $this->testUrl.'?slug=developers');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertCount(1, $client->getResponse()->toArray()['hydra:member']);
        $this->assertMatchesResourceCollectionJsonSchema(Quiz::class);
        $content = json_decode($client->getResponse()->getContent(), true);
        $quizUrl = $content['hydra:member'][0]['@id'];
        // amonymous
        $client->request(Request::METHOD_GET, $quizUrl.'/questions');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertCount(2, $client->getResponse()->toArray()['hydra:member']);
        $this->assertMatchesResourceCollectionJsonSchema(Question::class);
        // user
        $client = $this->getJwtClient();
        $client->request(Request::METHOD_GET, $quizUrl.'/questions');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertCount(2, $client->getResponse()->toArray()['hydra:member']);
        $this->assertMatchesResourceCollectionJsonSchema(Question::class);
        // editor
        $client = $this->getJwtClient(null, ['ROLE_EDITOR']);
        $client->request(Request::METHOD_GET, $quizUrl.'/questions');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertCount(2, $client->getResponse()->toArray()['hydra:member']);
        $this->assertMatchesResourceCollectionJsonSchema(Question::class);
        // admin
        $client = $this->getJwtClient(null, ['ROLE_ADMIN']);
        $client->request(Request::METHOD_GET, $quizUrl.'/questions');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertCount(2, $client->getResponse()->toArray()['hydra:member']);
        $this->assertMatchesResourceCollectionJsonSchema(Question::class);
    }

    /**
     * @test
     * @large
     * @group Functional
     * @group Quiz
     */
    public function results()
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, $this->testUrl.'?slug=developers');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertCount(1, $client->getResponse()->toArray()['hydra:member']);
        $this->assertMatchesResourceCollectionJsonSchema(Quiz::class);
        $content = json_decode($client->getResponse()->getContent(), true);
        $quizUrl = $content['hydra:member'][0]['@id'];
        // amonymous
        $client->request(Request::METHOD_GET, $quizUrl.'/results');
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }
}