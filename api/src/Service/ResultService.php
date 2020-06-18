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
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;

class ResultService
{
    private EntityManagerInterface $em;
    private Serializer $serializer;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
//         $encoders = [new JsonEncoder()];
//         $normolizers = [new ObjectNormalizer()];
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $this->serializer = new Serializer(
            [$normalizer],
            [new JsonEncoder()]
        );
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
        $userAnswer = new UserAnswer();
        $userAnswer->setQuestion($question);
        $userAnswer->setAnswer($answer);
        $data = $this->serializer->serialize($userAnswer, 'json', ['groups' => 'user:answer']);
        $result->addResult($data);
        $this->em->persist($result);
        $this->em->flush();
        return $result;
    }
}
