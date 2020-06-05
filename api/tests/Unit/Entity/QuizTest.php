<?php declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use PHPUnit\Framework\TestCase;
use App\Entity\Quiz;
use Doctrine\Common\Collections\ArrayCollection;
use App\Tests\TestHelper;
use App\Entity\Question;
use App\Entity\Result;
use Carbon\Carbon;

class QuizTest extends TestCase
{
    /**
     * @test
     * @group Unit
     * @group Entity
     */
    public function properties()
    {
        $this->assertClassHasAttribute('id', Quiz::class);
        $this->assertClassHasAttribute('name', Quiz::class);
        $this->assertClassHasAttribute('description', Quiz::class);
        $this->assertClassHasAttribute('slug', Quiz::class);
        $this->assertClassHasAttribute('questions', Quiz::class);
        $this->assertClassHasAttribute('results', Quiz::class);
        $this->assertClassHasAttribute('createdAt', Quiz::class);
    }

    /**
     * @test
     * @group Unit
     * @group Entity
     */
    public function defaultValues()
    {
        $entity = new Quiz();
        $this->assertNull($entity->getId());
        $this->assertEmpty($entity->getName());
        $this->assertEmpty($entity->getDescription());
        $this->assertEmpty($entity->getSlug());
        $this->assertInstanceOf(ArrayCollection::class, $entity->getQuestions());
        $this->assertCount(0, $entity->getQuestions());
        $this->assertInstanceOf(ArrayCollection::class, $entity->getResults());
        $this->assertCount(0, $entity->getResults());
        $this->assertNull($entity->getCreatedAt());
    }

    /**
     * @test
     * @group Unit
     * @group Entity
     */
    public function values()
    {
        $name = TestHelper::getTestString();
        $description = TestHelper::getTestString();
        $slug = TestHelper::getTestString();
        $question = $this->createMock(Question::class);
        $result = $this->createMock(Result::class);
        $createdAt = Carbon::now()->subDay();
        $entity = new Quiz();
        // name
        $this->assertInstanceOf(Quiz::class, $entity->setName($name));
        $this->assertIsString($entity->getName());
        $this->assertEquals($name, $entity->getName());
        // description
        $this->assertInstanceOf(Quiz::class, $entity->setDescription($description));
        $this->assertIsString($entity->getDescription());
        $this->assertEquals($description, $entity->getDescription());
        // slug
        $this->assertInstanceOf(Quiz::class, $entity->setSlug($slug));
        $this->assertIsString($entity->getSlug());
        $this->assertEquals($slug, $entity->getSlug());
        // questions
        $this->assertInstanceOf(Quiz::class, $entity->addQuestion($question));
        $this->assertCount(1, $entity->getQuestions());
        $this->assertEquals($question, $entity->getQuestions()->first());
        $this->assertInstanceOf(Quiz::class, $entity->removeQuestion($question));
        $this->assertCount(0, $entity->getQuestions());
        // results
        $this->assertInstanceOf(Quiz::class, $entity->addResult($result));
        $this->assertCount(1, $entity->getResults());
        $this->assertEquals($result, $entity->getResults()->first());
        $this->assertInstanceOf(Quiz::class, $entity->removeResult($result));
        $this->assertCount(0, $entity->getResults());
        // created at
        $this->assertInstanceOf(Quiz::class, $entity->setCreatedAt($createdAt));
        $this->assertInstanceOf(Carbon::class, $entity->getCreatedAt());
        $this->assertEquals($createdAt, $entity->getCreatedAt());
    }

    /**
     * @test
     * @group Unit
     * @group Entity
     */
    public function generateValues()
    {
        $entity = new Quiz();
        $name = "asd-AAf-78 45";
        $entity->setName($name);
        $entity->generateSlug();
        $this->assertEquals('asd-aaf-78-45', $entity->getSlug());
    }
}

