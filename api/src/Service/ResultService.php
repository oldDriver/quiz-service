<?php declare(strict_types=1);
namespace App\Service;

use App\Entity\User;
use App\Entity\Quiz;
use App\Entity\Result;
use Doctrine\ORM\EntityManagerInterface;

class ResultService
{
    private EntityManagerInterface $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    public function startQuiz(User $user, Quiz $quiz): Result
    {
        $result = new Result();
        $result->setQuiz($quiz);
        $result->setUserId($user->getId());
        $this->em->persist($result);
        $this->em->flush();
        return $result;
    }
}
