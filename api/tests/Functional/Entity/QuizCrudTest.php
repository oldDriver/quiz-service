<?php declare(strict_types=1);

namespace App\Tests\Functional\Entity;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\BaseDatabaseTrait;
use App\Tests\Functional\FunctionalTestTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Quiz;
use App\Tests\TestHelper;

class QuizCrudTest extends ApiTestCase
{
    use BaseDatabaseTrait;
    use FunctionalTestTrait;
    private string $testUrl = '/quizzes';
    private static $quizUrl = '';
    public static function setUpBeforeClass(): void
    {
        static::bootKernel();
        static::populateDatabase();
    }

    /**
     * @test
     * @group Functional
     * @group Crud
     */
    public function createQuiz()
    {
        // aninymous
        $client = static::createClient();
        $client->request(Request::METHOD_POST, $this->testUrl, ['json' => $this->getTestQuiz()]);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        // user
//         $client = $this->getJwtClient();
//         $client->request(Request::METHOD_POST, $this->testUrl, ['json' => $this->getTestQuiz()]);
//         $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        // editor
        $client = $this->getJwtClient(null, ['ROLE_EDITOR']);
        $client->request(Request::METHOD_POST, $this->testUrl, ['json' => $this->getTestQuiz()]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertRegExp('~^/quizzes/\d+$~', $client->getResponse()->toArray()['@id']);
        self::$quizUrl = $client->getResponse()->toArray()['@id'];
        $this->assertMatchesResourceItemJsonSchema(Quiz::class);
        // editor try to create quiz with the same name
        $client->request(Request::METHOD_POST, $this->testUrl, ['json' => $this->getTestQuiz()]);
        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        // admin
        $client = $this->getJwtClient(null, ['ROLE_ADMIN']);
        $request = $this->getTestQuiz();
        $request['name'] = 'Admin Crud';
        $client->request(Request::METHOD_POST, $this->testUrl, ['json' => $request]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertRegExp('~^/quizzes/\d+$~', $client->getResponse()->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Quiz::class);
        
    }

    /**
     * @test
     * @group Functional
     * @group Crud
     */
    public function readQuiz()
    {
        // anonymous
        $client = static::createClient();
        $iri = static::findIriBy(Quiz::class, ['slug' => 'test-crud']);
        $client->request(Request::METHOD_GET, $iri);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesResourceItemJsonSchema(Quiz::class);
    }

    /**
     * @test
     * @group Functional
     * @group Crud
     */
    public function updateQuiz()
    {
        $description = TestHelper::getTestString();
        $request = ['description' => $description];
        // anonymous
        $client = static::createClient();
        $iri = static::findIriBy(Quiz::class, ['slug' => 'test-crud']);
//         $client->request(Request::METHOD_POST, $iri, ['json' => $request]);
//         $this->assertResponseStatusCodeSame(Response::HTTP_METHOD_NOT_ALLOWED);
//         $client->request(Request::METHOD_PUT, $iri, ['json' => $request]);
//         $this->assertResponseStatusCodeSame(Response::HTTP_METHOD_NOT_ALLOWED);
//         $client->request(Request::METHOD_PATCH, $iri, ['json' => $request]);
//         $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
//         // user
//         $client = $this->getJwtClient();
//         $client->request(Request::METHOD_PATCH, $iri, ['json' => $request]);
//         $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
//         // editor
        $client = $this->getJwtEditorClient();
//         $client->request(
//             Request::METHOD_PATCH,
//             $iri,
//             [
//                 'json' => $request
//             ]
//         );
//         $this->assertResponseStatusCodeSame(Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
        $client->request(
            Request::METHOD_PATCH,
            $iri,
            [
                'headers' => ['Content-Type' => 'application/merge-patch+json'],
                'json' => $request
            ]
        );
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesResourceItemJsonSchema(Quiz::class);
        $array = $client->getResponse()->toArray();
        $this->assertEquals($description, $array['description']);
        // admin changed description
        $client = $this->getJwtAdminClient();
        $description = TestHelper::getTestString();
        $request = ['description' => $description];
        $client->request(
            Request::METHOD_PATCH,
            $iri,
            [
                'headers' => ['Content-Type' => 'application/merge-patch+json'],
                'json' => $request
            ]
        );
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesResourceItemJsonSchema(Quiz::class);
        $array = $client->getResponse()->toArray();
        $this->assertEquals($description, $array['description']);
    }

    /**
     * @test
     * @group Functional
     * @group Crud
     */
    public function deleteQuiz()
    {
        // anonymous
        $client = static::createClient();
        $iri = static::findIriBy(Quiz::class, ['slug' => 'test-crud']);
        $client->request(Request::METHOD_DELETE, $iri);
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
//         // user
//         $client = $this->getJwtClient();
//         $client->request(Request::METHOD_DELETE, $iri);
//         $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
//         // editor
//         $client = $this->getJwtEditorClient();
//         $client->request(Request::METHOD_DELETE, $iri);
//         $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        // admin
        $client = $this->getJwtAdminClient();
        $client->request(Request::METHOD_DELETE, $iri);
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}
