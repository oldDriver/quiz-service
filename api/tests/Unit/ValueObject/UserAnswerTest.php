<?php declare(strict_types=1);

namespace App\Tests\Unit\ValueObject;

use PHPUnit\Framework\TestCase;
use App\ValueObject\UserAnswer;
use App\Entity\Question;
use App\Entity\Answer;
use Symfony\Component\Validator\Validation;

class UserAnswerTest extends TestCase
{
    /**
     * @test
     * @group Unit
     */
    public function properties()
    {
        $this->assertClassHasAttribute('question', UserAnswer::class);
        $this->assertClassHasAttribute('answer', UserAnswer::class);
    }

    /**
     * @test
     * @group Unit
     */
    public function defaultValues()
    {
        $object = new UserAnswer();
        $this->assertNull($object->getQuestion());
        $this->assertNull($object->getAnswer());
    }

    /**
     * @test
     * @group Unit
     */
    public function values()
    {
        $question = $this->createMock(Question::class);
        $answer = $this->createMock(Answer::class);
        $object = new UserAnswer();
        // question
        $this->assertInstanceOf(UserAnswer::class, $object->setQuestion($question));
        $this->assertInstanceOf(Question::class, $object->getQuestion());
        $this->assertEquals($question, $object->getQuestion());
        // answer
        $this->assertInstanceOf(UserAnswer::class, $object->setAnswer($answer));
        $this->assertInstanceOf(Answer::class, $object->getAnswer());
        $this->assertEquals($answer, $object->getAnswer());
    }

    /**
     * @test
     * @group Unit
     */
    public function validation()
    {
        $validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
        $object = new UserAnswer();
        $errors = $validator->validate($object);
        $this->assertCount(2, $errors);
        $question = $this->createMock(Question::class);
        $object->setQuestion($question);
        $errors = $validator->validate($object);
        $this->assertCount(1, $errors);
        $answer = $this->createMock(Answer::class);
        $object->setAnswer($answer);
        $errors = $validator->validate($object);
        $this->assertCount(0, $errors);
    }
}
