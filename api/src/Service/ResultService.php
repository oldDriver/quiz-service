<?php declare(strict_types=1);
namespace App\Service;

use App\Entity\User;
use App\Entity\Quiz;
use App\Entity\Result;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Question;
use App\Entity\Answer;
use Symfony\Component\VarDumper\VarDumper;
use App\Entity\UserAnswer;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;
use Symfony\Component\Serializer\Serializer;

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

    public function addAnswer(User $user, Result $result, Question $question, Answer $answer): Result
    {
        $answers = $result->getResult();
        $userAnswer = new UserAnswer();
        $userAnswer->setQuestion($question);
        $userAnswer->setAnswer($answer);
        $answers[] = $userAnswer;
        $result->setResult($answers);
        $this->em->persist($result);
        $this->em->flush();
        return $result;
    }
}
