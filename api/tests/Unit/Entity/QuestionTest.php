<?php declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Question;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Quiz;
use App\Tests\TestHelper;
use App\Entity\Answer;
use Symfony\Component\Validator\Validation;

class QuestionTest extends TestCase
{
    /**
     * @test
     * @group Unit
     * @group Entity
     */
    public function properties(): void
    {
        $this->assertClassHasAttribute('id', Question::class);
        $this->assertClassHasAttribute('quiz', Question::class);
        $this->assertClassHasAttribute('number', Question::class);
        $this->assertClassHasAttribute('question', Question::class);
        $this->assertClassHasAttribute('slug', Question::class);
        $this->assertClassHasAttribute('answers', Question::class);
    }

    /**
     * @test
     * @group Unit
     * @group Entity
     */
    public function defaultValues(): void
    {
        $entity = new Question();
        $this->assertNull($entity->getId());
        $this->assertNull($entity->getQuiz());
        $this->assertEquals(1, $entity->getNumber());
        $this->assertEmpty($entity->getQuestion());
        $this->assertEmpty($entity->getSlug());
        $this->assertInstanceOf(ArrayCollection::class, $entity->getAnswers());
        $this->assertCount(0, $entity->getAnswers());
    }

    /**
     * @test
     * @group Unit
     * @group Entity
     */
    public function values()
    {
        $entity = new Question();
        // quiz
        $quiz = $this->createMock(Quiz::class);
        $this->assertInstanceOf(Question::class, $entity->setQuiz($quiz));
        $this->assertInstanceOf(Quiz::class, $entity->getQuiz());
        $this->assertEquals($quiz, $entity->getQuiz());
        // number
        $number = TestHelper::getTestInt();
        $this->assertInstanceOf(Question::class, $entity->setNumber($number));
        $this->assertIsInt($entity->getNumber());
        $this->assertEquals($number, $entity->getNumber());
        // question
        $question = TestHelper::getTestString();
        $this->assertInstanceOf(Question::class, $entity->setQuestion($question));
        $this->assertIsString($entity->getQuestion());
        $this->assertEquals($question, $entity->getQuestion());
        // slug
        $slug = TestHelper::getTestString();
        $this->assertInstanceOf(Question::class, $entity->setSlug($slug));
        $this->assertIsString($entity->getSlug());
        $this->assertEquals($slug, $entity->getSlug());
        // answers
        $answer = $this->createMock(Answer::class);
        $this->assertInstanceOf(Question::class, $entity->addAnswer($answer));
        $this->assertInstanceOf(ArrayCollection::class, $entity->getAnswers());
        $this->assertCount(1, $entity->getAnswers());
        $this->assertEquals($answer, $entity->getAnswers()->first());
        $this->assertInstanceOf(Question::class, $entity->removeAnswer($answer));
        $this->assertInstanceOf(ArrayCollection::class, $entity->getAnswers());
        $this->assertCount(0, $entity->getAnswers());
    }

    /**
     * @test
     * @group Unit
     * @group Entity
     */
    public function generateValues()
    {
        $entity = new Question();
        $quiz = $this->createMock(Quiz::class);
        $quiz->expects($this->once())->method('getName')->willReturn('Test quiz');
        $entity->setQuiz($quiz);
        $entity->generateSlug();
        $this->assertEquals('test-quiz-1', $entity->getSlug());
    }

    /**
     * @test
     * @group Unit
     * @group Entity
     */
    public function validate()
    {
        $validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
        $entity = new Question();
        $errors = $validator->validate($entity);
        $this->assertCount(2, $errors);
        // quiz
        $quiz = $this->createMock(Quiz::class);
        $entity->setQuiz($quiz);
        $errors = $validator->validate($entity);
        $this->assertCount(1, $errors);
        // question
        $question = TestHelper::getTestString();
        $entity->setQuestion($question);
        $errors = $validator->validate($entity);
        $this->assertCount(0, $errors);
    }
}
