<?php
namespace App\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ResultService;
use App\ValueObject\UserAnswer;
use App\Entity\Result;
use App\Entity\Quiz;
use App\Security\User;
use App\Tests\TestHelper;
use Doctrine\Common\Collections\Collection;
use App\Entity\Question;

class ResultServiceTest extends TestCase
{
    /**
     * @test
     * @group Unit
     */
    public function createUserAnswer()
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $service = new ResultService($em);
        $this->assertInstanceOf(UserAnswer::class, $service->createUserAnswer());
    }

    /**
     * @test
     * @group Unit
     * @group Service
     */
    public function createUserResult()
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $service = new ResultService($em);
        $this->assertInstanceOf(Result::class, $service->createUserResult());
    }

    /**
     * @test
     * @group Unit
     * @group Service
     */
    public function startQuiz()
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $quiz = $this->createMock(Quiz::class);
        $user = $this->createMock(User::class);
        $result = $this->createMock(Result::class);
        $collection = $this->createMock(Collection::class);
        $id = TestHelper::getTestInt();
        $count = TestHelper::getTestInt();
        // behavior
        $user->expects($this->once())->method('getId')->willReturn($id);
        $quiz->expects($this->once())->method('getQuestions')->willReturn($collection);
        $collection->expects($this->once())->method('count')->willReturn($count);
        $service = $this->getMockBuilder(ResultService::class)->setConstructorArgs([$em])->setMethodsExcept(['startQuiz'])->getMock();
        $service->expects($this->once())->method('createUserResult')->willReturn($result);
        $result->expects($this->once())->method('setQuiz')->with($quiz)->willReturnSelf();
        $result->expects($this->once())->method('setUserId')->with($id)->willReturnSelf();
        $result->expects($this->once())->method('setTotal')->with($count)->willReturnSelf();
        $em->expects($this->once())->method('persist')->with($result);
        $em->expects($this->once())->method('flush');
        // start test
        $this->assertInstanceOf(Result::class, $service->startQuiz($user, $quiz));
    }

    /**
     * @test
     * @group Unit
     * @group Service
     * @dataProvider isExistingQuestionCases
     * @param array $case
     * @param bool $expected
     */
    public function isExistingQuestion(array $case, bool $expected)
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $service = $this->getMockBuilder(ResultService::class)->setConstructorArgs([$em])->setMethodsExcept(['isExistingQuestion'])->getMock();
        $this->assertEquals($expected, $service->isExistingQuestion($case['arrayQuestions'], $case['question']));
    }

    /**
     * @return array
     */
    public function isExistingQuestionCases(): array
    {
        $duplicatedQuestion = $this->createMock(Question::class);
        $duplicatedQuestion->method('getId')->willReturn(12713);
        $newQuestion = $this->createMock(Question::class);
        $newQuestion->method('getId')->willReturn(1);
        $arrayQuestions = ["{\"question\":{\"id\":12713,\"number\":1,\"question\":\"Abstract Factory\",\"answers\":[{\"answer\":\"Creational\",\"isRight\":true},{\"answer\":\"Structural\",\"isRight\":false},{\"answer\":\"Behavioral\",\"isRight\":false},{\"answer\":\"Functional\",\"isRight\":false},{\"answer\":\"Concurrency\",\"isRight\":false},{\"answer\":\"Architectural\",\"isRight\":false}]},\"answer\":{\"answer\":\"Creational\",\"isRight\":true}}"];
        return [
            [
                'case' => [
                    'arrayQuestions' => $arrayQuestions,
                    'question' => $duplicatedQuestion
                ],
                'expected' => true
            ],
            [
                'case' => [
                    'arrayQuestions' => $arrayQuestions,
                    'question' => $newQuestion
                ],
                'expected' => false
            ],
        ];
    }
}
