<?php declare(strict_types=1);

namespace App\Tests\Functional\Entity;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\BaseDatabaseTrait;
use App\Tests\Functional\FunctionalTestTrait;
use App\Entity\Quiz;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Question;

class QuestionCollectionTest extends ApiTestCase
{
    use BaseDatabaseTrait;
    use FunctionalTestTrait;

    public static function setUpBeforeClass(): void
    {
        static::bootKernel();
        static::populateDatabase();
    }

    /**
     * @test
     * @group Functional
     * @group Question
     */
    public function collection()
    {
        // anonymous
        $client = static::createClient();
        $iri = $this->findIriBy(Quiz::class, ['slug' => 'quiz-for-developers']);
        $iri .= '/questions';
        $client->request(Request::METHOD_GET, $iri);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertCount(2, $client->getResponse()->toArray()['hydra:member']);
        $this->assertMatchesResourceCollectionJsonSchema(Question::class);
        
    }
}
