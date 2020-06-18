<?php declare(strict_types=1);

namespace App\Tests\Functional\Scenarios;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\BaseDatabaseTrait;
use App\Tests\Functional\FunctionalTestTrait;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Quiz;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarDumper\VarDumper;
use App\Entity\Result;

/**
 * User select Quiz and start it without answers
 *
 */
class UserStartQuizTest extends ApiTestCase
{
    use BaseDatabaseTrait;
    use FunctionalTestTrait;
    private string $testUrl = '/results';
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
        //VarDumper::dump($iri);
        $client->request(Request::METHOD_POST, $this->testUrl, ['json' => ['quizIri' => $iri]]);
        //VarDumper::dump(json_decode($client->getResponse()->getContent(false), true));
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertRegExp('~^/results/\d+$~', $client->getResponse()->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Result::class);
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
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        // check results
        $client->request(Request::METHOD_GET, $this->resultsUrl);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertCount(1, $client->getResponse()->toArray()['hydra:member']);
    }
}
