<?php
namespace App\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Question;
use App\Service\QuestionService;
use App\Entity\Quiz;

class QuestionServiceTest extends TestCase
{
    /**
     * @test
     * @group Unit
     * @group Service
     */
    public function fixQuizNumbersRightOrder()
    {
        // mocks
        $em = $this->createMock(EntityManagerInterface::class);
        $quiz = $this->createMock(Quiz::class);
        $question1 = $this->createMock(Question::class);
        $question2 = $this->createMock(Question::class);
        // behavior
        $question1->expects($this->once())->method('getNumber')->willReturn(1);
        $question2->expects($this->once())->method('getNumber')->willReturn(2);
        $service = $this->getMockBuilder(QuestionService::class)->setConstructorArgs([$em])->setMethodsExcept(['fixQuizNumbers'])->getMock();
        $service->expects($this->once())->method('getQuestionsByQuiz')->with($quiz)->willReturn([$question1, $question2]);
        // start test
        $counter = $service->fixQuizNumbers($quiz);
        $this->assertIsInt($counter);
        $this->assertEquals(3, $counter);
    }

    /**
     * @test
     * @group Unit
     * @group Service
     */
    public function fixQuizNumbersWrongOrder()
    {
        // mocks
        $em = $this->createMock(EntityManagerInterface::class);
        $quiz = $this->createMock(Quiz::class);
        $question1 = $this->createMock(Question::class);
        $question2 = $this->createMock(Question::class);
        // behavior
        $question1->expects($this->once())->method('getNumber')->willReturn(2);
        $question2->expects($this->once())->method('getNumber')->willReturn(5);
        $question1->expects($this->once())->method('setNumber')->with(1)->willReturnSelf();
        $question2->expects($this->once())->method('setNumber')->with(2)->willReturnSelf();
        $question1->expects($this->once())->method('generateSlug');
        $question2->expects($this->once())->method('generateSlug');
        $em->expects($this->any())->method('persist')->with($question1);
        $em->expects($this->any())->method('persist')->with($question2);
        $em->expects($this->once())->method('flush');
        $service = $this->getMockBuilder(QuestionService::class)->setConstructorArgs([$em])->setMethodsExcept(['fixQuizNumbers'])->getMock();
        $service->expects($this->once())->method('getQuestionsByQuiz')->with($quiz)->willReturn([$question1, $question2]);
        // start test
        $counter = $service->fixQuizNumbers($quiz);
        $this->assertIsInt($counter);
        $this->assertEquals(3, $counter);
    }

    /**
     * @test
     * @group Unit
     * @group Service
     * @dataProvider setNumberCases
     */
    public function setNumber(array $case, int $expected)
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $quiz = $this->createMock(Quiz::class);
        $newQuestion = $this->createMock(Question::class);
        // behavior
        $newQuestion->expects($this->once())->method('getQuiz')->willReturn($quiz);
        $newQuestion->method('setNumber')->with($expected);
        $newQuestion->method('generateSlug');
        $service = $this->getMockBuilder(QuestionService::class)->setConstructorArgs([$em])->setMethodsExcept(['setNumber'])->getMock();
        $service->expects($this->once())->method('getQuestionsByQuiz')->with($quiz)->willReturn($case['questions']);
        $service->method('fixQuizNumbers')->willReturn($case['counter']);
        // start test
        $question = $service->setNumber($newQuestion);
        $this->assertInstanceOf(Question::class, $question);
        $this->assertEquals($newQuestion, $question);
    }

    public function setNumberCases(): array
    {
        $question1 = $this->createMock(Question::class);
        $question2 = $this->createMock(Question::class);
        return [
            [
                'case' => [
                    'questions' => [],
                    'counter' => 5// dummy counter
                ],
                'expected' => 1
            ],
            [
                'case' => [
                    'questions' => [$question1],
                    'counter' => 2
                ],
                'expected' => 2
            ],
            [
                'case' => [
                    'questions' => [$question1, $question2],
                    'counter' => 3
                ],
                'expected' => 3
            ],
            
        ];
    }
}
