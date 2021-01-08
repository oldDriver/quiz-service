<?php declare(strict_types=1);

namespace App\Tests\Functional\Entity;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\BaseDatabaseTrait;
use App\Tests\Functional\FunctionalTestTrait;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Question;
use Symfony\Component\HttpFoundation\Response;

class QuestionCrudTest extends ApiTestCase
{
    use BaseDatabaseTrait;
    use FunctionalTestTrait;
    private string $testUrl = '/questions';
    public static function setUpBeforeClass(): void
    {
        static::bootKernel();
        static::populateDatabase();
    }

    /**
     * @test
     * @group Functional
     * @group QuestionCrud
     */
    public function createQuestion()
    {
        $client = $this->getJwtEditorClient();
        $client->request(Request::METHOD_POST, $this->testUrl, ['json' => $this->getQuestionCreateArray()]);
        $this->assertResponseIsSuccessful();
        $client = $this->getJwtAdminClient();
        $iri = $this->findIriBy(Question::class, ['slug' => 'quiz-for-developers-2']);
        $client->request(Request::METHOD_DELETE, $iri);
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
    }
}