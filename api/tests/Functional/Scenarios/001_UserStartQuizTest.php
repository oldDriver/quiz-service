<?php declare(strict_types=1);

namespace App\Tests\Functional\Scenarios;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\BaseDatabaseTrait;
use App\Tests\Functional\FunctionalTestTrait;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Quiz;
use Symfony\Component\HttpFoundation\Response;

/**
 * User select Quiz and start it without answers
 *
 */
class UserStartQuizTest extends ApiTestCase
{
    use BaseDatabaseTrait;
    use FunctionalTestTrait;
    private string $testUrl = '/quiz_start_commands';
    private string $resultsUrl = '/results';
    public static function setUpBeforeClass(): void
    {
        static::bootKernel();
        static::populateDatabase();
    }

    /**
     * @test
     * @group Scenarios
     */
    public function scenario()
    {
        $client = $this->getJwtClient();
        // Check users results
        $client->request(Request::METHOD_GET, $this->resultsUrl);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertCount(0, $client->getResponse()->toArray()['hydra:member']);
        // User start quiz
        $iri = $this->findIriBy(Quiz::class, ['slug' => 'design-patterns']);
        $client->request(Request::METHOD_POST, $this->testUrl, ['json' => ['quizIri' => $iri]]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_ACCEPTED);
        // check results
        $client->request(Request::METHOD_GET, $this->resultsUrl);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertCount(1, $client->getResponse()->toArray()['hydra:member']);
        // User start quiz again
        $iri = $this->findIriBy(Quiz::class, ['slug' => 'design-patterns']);
        $client->request(Request::METHOD_POST, $this->testUrl, ['json' => ['quizIri' => $iri]]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_ACCEPTED);
        // check results
        $client->request(Request::METHOD_GET, $this->resultsUrl);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertCount(1, $client->getResponse()->toArray()['hydra:member']);
    }
}
