<?php
namespace App\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Answer;
use App\Entity\Question;
use App\Tests\TestHelper;
use Symfony\Component\Validator\Validation;

class AnswerTest extends TestCase
{
    /**
     * @test
     * @group Unit
     * @group Entity
     */
    public function properties()
    {
        $this->assertClassHasAttribute('id', Answer::class);
        $this->assertClassHasAttribute('question', Answer::class);
        $this->assertClassHasAttribute('answer', Answer::class);
        $this->assertClassHasAttribute('isRight', Answer::class);
    }

    /**
     * @test
     * @group Unit
     * @group Entity
     */
    public function defaultValues()
    {
        $entity = new Answer();
        $this->assertNull($entity->getId());
        $this->assertNull($entity->getQuestion());
        $this->assertEmpty($entity->getAnswer());
        $this->assertNull($entity->getIsRight());
    }

    /**
     * @test
     * @group Unit
     * @group Entity
     */
    public function values()
    {
        $question = $this->createMock(Question::class);
        $answer = TestHelper::getTestString();
        $isRight = TestHelper::getTestBool();
        $entity = new Answer();
        // question
        $this->assertInstanceOf(Answer::class, $entity->setQuestion($question));
        $this->assertInstanceOf(Question::class, $entity->getQuestion());
        $this->assertEquals($question, $entity->getQuestion());
        // answer
        $this->assertInstanceOf(Answer::class, $entity->setAnswer($answer));
        $this->assertIsString($entity->getAnswer());
        $this->assertEquals($answer, $entity->getAnswer());
        // is right answer
        $this->assertInstanceOf(Answer::class, $entity->setIsRight($isRight));
        $this->assertIsBool($entity->getIsRight());
        $this->assertEquals($isRight, $entity->getIsRight());
    }

    /**
     * @test
     * @group Unit
     * @group Entity
     */
    public function validation()
    {
        $validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
        $entity = new Answer();
        $errors = $validator->validate($entity);
        $this->assertCount(3, $errors);
        $question = $this->createMock(Question::class);
        $entity->setQuestion($question);
        $errors = $validator->validate($entity);
        $this->assertCount(2, $errors);
        $answer = TestHelper::getTestString();
        $entity->setAnswer($answer);
        $errors = $validator->validate($entity);
        $this->assertCount(1, $errors);
        $isRight = TestHelper::getTestBool();
        $entity->setIsRight($isRight);
        $errors = $validator->validate($entity);
        $this->assertCount(0, $errors);
    }
}
