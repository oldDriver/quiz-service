<?php declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Result;
use App\Tests\TestHelper;
use App\Entity\Quiz;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Symfony\Component\Validator\Validation;

class ResultTest extends TestCase
{
    /**
     * @test
     * @group Unit
     * @group Entity
     */
    public function properties()
    {
        $this->assertClassHasAttribute('id', Result::class);
        $this->assertClassHasAttribute('userId', Result::class);
        $this->assertClassHasAttribute('quiz', Result::class);
        $this->assertClassHasAttribute('result', Result::class);
        $this->assertClassHasAttribute('score', Result::class);
        $this->assertClassHasAttribute('createdAt', Result::class);
        $this->assertClassHasAttribute('updatedAt', Result::class);
    }

    /**
     * @test
     * @group Unit
     * @group Entity
     */
    public function defaultValues()
    {
        $entity = new Result();
        $this->assertNull($entity->getId());
        $this->assertNull($entity->getUserId());
        $this->assertNull($entity->getQuiz());
        $this->assertEmpty($entity->getResult());
        $this->assertEquals(0, $entity->getScore());
        $this->assertNull($entity->getCreatedAt());
        $this->assertNull($entity->getUpdatedAt());
    }

    /**
     * @test
     * @group Unit
     * @group Entity
     */
    public function values()
    {
        $userId = TestHelper::getTestInt();
        $quiz = $this->createMock(Quiz::class);
        $result = [TestHelper::getTestString() => TestHelper::getTestInt()];
        $score = floatval(TestHelper::getTestInt() / 100);
        $createdAt = Carbon::now()->subMonth();
        $updatedAt = Carbon::now()->subWeek();
        $entity = new Result();
        // userId
        $this->assertInstanceOf(Result::class, $entity->setUserId($userId));
        $this->assertIsInt($entity->getUserId());
        $this->assertEquals($userId, $entity->getUserId());
        // quiz
        $this->assertInstanceOf(Result::class, $entity->setQuiz($quiz));
        $this->assertInstanceOf(Quiz::class, $entity->getQuiz());
        $this->assertEquals($quiz, $entity->getQuiz());
        // result
        $this->assertInstanceOf(Result::class, $entity->setResult($result));
        $this->assertIsArray($entity->getResult());
        $this->assertEquals($result, $entity->getResult());
        // score
        $this->assertInstanceOf(Result::class, $entity->setScore($score));
        $this->assertIsFloat($entity->getScore());
        $this->assertEquals($score, $entity->getScore());
        // createdAt
        $this->assertInstanceOf(Result::class, $entity->setCreatedAt($createdAt));
        $this->assertInstanceOf(Carbon::class, $entity->getCreatedAt());
        $this->assertEquals($createdAt, $entity->getCreatedAt());
        // updatedAt
        $this->assertInstanceOf(Result::class, $entity->setUpdatedAt($updatedAt));
        $this->assertInstanceOf(CarbonInterface::class, $entity->getUpdatedAt());
        $this->assertEquals($updatedAt, $entity->getUpdatedAt());
    }

    /**
     * @test
     * @group Unit
     * @group Entity
     */
    public function generateValues()
    {
        $entity = new Result();
        // created At
        $this->assertInstanceOf(Result::class, $entity->generateCreatedAt());
        $this->assertInstanceOf(Carbon::class, $entity->getCreatedAt());
        $createdAt = $entity->getCreatedAt();
        $this->assertInstanceOf(Result::class, $entity->generateCreatedAt());
        $this->assertInstanceOf(Carbon::class, $entity->getCreatedAt());
        $this->assertEquals($createdAt, $entity->getCreatedAt());
        // updatedAt
        $this->assertNull($entity->getUpdatedAt());
        $this->assertInstanceOf(Result::class, $entity->generateUpdatedAt());
        $this->assertInstanceOf(Carbon::class, $entity->getUpdatedAt());
        $updatedAt = $entity->getUpdatedAt();
        $this->assertInstanceOf(Result::class, $entity->generateUpdatedAt());
        $this->assertInstanceOf(Carbon::class, $entity->getUpdatedAt());
        $this->assertGreaterThan($updatedAt, $entity->getUpdatedAt());
    }

    /**
     * @test
     * @group Unit
     * @group Entity
     */
    public function validation()
    {
        $validator = Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
        $entity = new Result();
        $errors = $validator->validate($entity);
        $this->assertCount(2, $errors);
        // userId
        $userId = TestHelper::getTestInt();
        $entity->setUserId($userId);
        $errors = $validator->validate($entity);
        $this->assertCount(1, $errors);
        // quiz
        $quiz = $this->createMock(Quiz::class);
        $entity->setQuiz($quiz);
        $errors = $validator->validate($entity);
        $this->assertCount(0, $errors);
    }
}
