<?php
namespace App\Tests\Unit\Service;

use PHPUnit\Framework\TestCase;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ResultService;
use App\ValueObject\UserAnswer;
use App\Entity\Result;

class ResultserviceTest extends TestCase
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
}
