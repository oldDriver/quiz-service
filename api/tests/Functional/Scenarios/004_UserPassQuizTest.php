<?php declare(strict_types=1);

namespace App\Tests\Functional\Scenarios;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use Hautelook\AliceBundle\PhpUnit\BaseDatabaseTrait;
use App\Tests\Functional\FunctionalTestTrait;
use App\Entity\Quiz;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Question;
use App\Entity\Result;

class UserPassQuizTest extends ApiTestCase
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
     * @group Functional
     * @group PassQuiz
     */
    public function scenario()
    {
        $client = $this->getJwtClient();
        // User start quiz
        $quizIri = $this->findIriBy(Quiz::class, ['slug' => 'quiz-for-developers']);
        $client->request(Request::METHOD_POST, $this->testUrl, ['json' => ['quizIri' => $quizIri]]);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        //$this->assertRegExp('~^/results/\d+$~', $client->getResponse()->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Result::class);
        $resultIri =  $client->getResponse()->toArray()['@id'];
        // user get first question
        $questionIri = $this->findIriBy(Question::class, ['slug' => 'quiz-for-developers-1']);
        $client->request(Request::METHOD_GET, $questionIri);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesResourceItemJsonSchema(Question::class);
        $question = $client->getResponse()->toArray();
        $this->assertArrayHasKey('answers', $question);
        $answers = $question['answers'];
        $this->assertIsArray($answers);
        // user select first answer, but this question has no right answer
        $answer = array_shift($answers);
        $this->assertIsArray($answer);
        $this->assertArrayHasKey('@id', $answer);
        $this->assertArrayHasKey('answer', $answer);
        $answerIri = $answer['@id'];
        $client->request(
            Request::METHOD_PATCH,
            $resultIri,
            [
                'headers' => ['Content-Type' => 'application/merge-patch+json'],
                'json' => [
                    'quizIri' => $quizIri,
                    'questionIri' => $questionIri,
                    'answerIri' => $answerIri
                ]
            ]
        );
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesResourceItemJsonSchema(Result::class);
        // use get and answer second (last) question
        $questionIri = $this->findIriBy(Question::class, ['slug' => 'quiz-for-developers-2']);
        $client->request(Request::METHOD_GET, $questionIri);
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesResourceItemJsonSchema(Question::class);
        $question = $client->getResponse()->toArray();
        $this->assertArrayHasKey('answers', $question);
        $answers = $question['answers'];
        $this->assertIsArray($answers);
        // user select first answer, but this question has no right answer
        $answer = array_shift($answers);
        $this->assertIsArray($answer);
        $this->assertArrayHasKey('@id', $answer);
        $this->assertArrayHasKey('answer', $answer);
        $answerIri = $answer['@id'];
        $client->request(
            Request::METHOD_PATCH,
            $resultIri,
            [
                'headers' => ['Content-Type' => 'application/merge-patch+json'],
                'json' => [
                    'quizIri' => $quizIri,
                    'questionIri' => $questionIri,
                    'answerIri' => $answerIri
                ]
            ]
        );
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertMatchesResourceItemJsonSchema(Result::class);
        
    }
}
