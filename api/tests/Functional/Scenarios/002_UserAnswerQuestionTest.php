<?php declare(strict_types=1);

namespace App\Tests\Functional\Scenarios;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\BaseDatabaseTrait;
use App\Tests\Functional\FunctionalTestTrait;
use App\Entity\Quiz;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Question;
use Symfony\Component\VarDumper\VarDumper;
use App\Entity\Result;
use App\Dto\ResultOutput;

class UserAnswerQuestionTest extends ApiTestCase
{
    use BaseDatabaseTrait;
    use FunctionalTestTrait;
    private string $testUrl = '/results';
    public static function setUpBeforeClass(): void
    {
        static::bootKernel();
        static::populateDatabase();
    }
    /**
     * @test
     * @group Answer
     */
    public function scenario()
    {
        $client = $this->getJwtClient();
        // User start quiz
        $quizIri = $this->findIriBy(Quiz::class, ['slug' => 'design-patterns']);
        $quizId = str_replace('/quizzes/', '', $quizIri);
        $client->request(Request::METHOD_POST, $this->testUrl, ['json' => ['quizIri' => $quizIri]]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertRegExp('~^/results/\d+$~', $client->getResponse()->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Result::class);
        
//         VarDumper::dump(json_decode($client->getResponse()->getContent(), true));
        // User find results for this quiz
        //$client->request(Request::METHOD_GET, $this->resultsUrl.'?quiz_id='.$quizId);
//         VarDumper::dump(json_decode($client->getResponse()->getContent(false), true));
        //$this->assertResponseIsSuccessful();
//         $this->assertResponseStatusCodeSame(Response::HTTP_OK);
//         $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
//         $this->assertMatchesResourceItemJsonSchema(Result::class);
        // user get first question
//         $questionIri = $this->findIriBy(Question::class, ['slug' => 'design-patterns-1']);
//         $client->request(Request::METHOD_GET, $questionIri);
//         $this->assertResponseIsSuccessful();
//         $this->assertResponseStatusCodeSame(Response::HTTP_OK);
//         $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
//         $this->assertMatchesResourceItemJsonSchema(Question::class);
//         $question = $client->getResponse()->toArray();
//         $this->assertArrayHasKey('answers', $question);
//         $answers = $question['answers'];
//         $this->assertIsArray($answers);
//         // user select first answer (it is right, he-he)
//         $answer = array_shift($answers);
//         $this->assertIsArray($answer);
//         $this->assertArrayHasKey('@id', $answer);
//         $this->assertArrayHasKey('answer', $answer);
//         $answerIri = $answer['@id'];
//         //$client->request(Request::METHOD_PATCH, $url)

//         VarDumper::dump($answer);
    }
}
