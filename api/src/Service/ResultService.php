<?php declare(strict_types=1);
namespace App\Service;

use App\Entity\User;
use App\Entity\Quiz;
use App\Entity\Result;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Question;
use App\Entity\Answer;
use App\Entity\UserAnswer;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\VarDumper\VarDumper;

class ResultService
{
    private EntityManagerInterface $em;
    private Serializer $serializer;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $this->serializer = new Serializer(
            [$normalizer],
            [new JsonEncoder()]
        );
    }

    public function startQuiz(User $user, Quiz $quiz): Result
    {
        $result = $this->createResult();
        $result->setQuiz($quiz);
        $result->setUserId($user->getId());
        $result->setTotal($quiz->getQuestions()->count());
        $this->em->persist($result);
        $this->em->flush();
        return $result;
    }

    public function addAnswer(User $user, Result $result, Question $question, Answer $answer): ?Result
    {
        $data = $result->getResult();
        if ($this->isExistingQuestion($data, $question)) {
            return null;
        }
        $userAnswer = $this->createUserAnswer();
        $userAnswer->setQuestion($question);
        $userAnswer->setAnswer($answer);
        $data = $this->serializer->serialize($userAnswer, 'json', ['groups' => 'user:answer']);
        $result->addResult($data);
        $score = $result->getScore();
        if ($answer->getIsRight()) {
            $score++;
            $result->setScore($score);
        }
        $this->em->persist($result);
        $this->em->flush();
        return $result;
    }

    private function isExistingQuestion(array $arrayQuestions, Question $question): bool
    {
        $questionIds = [];
        foreach ($arrayQuestions as $item) {
            $arrayItem = json_decode($item, true);
            $questionIds[] = $arrayItem['question']['id'];
        }
        return in_array($question->getId(), $questionIds);
    }

    /**
     * Simple factory
     */
    public function createResult(): Result
    {
        return new Result();
    }

    public function createUserAnswer(): UserAnswer
    {
        return new UserAnswer();
    }
}
