<?php declare(strict_types=1);

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Question;
use App\Entity\Quiz;

class QuestionService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Just validate exisiting questions numbers and add latest number
     * @param Question $question
     * @return Question
     */
    public function setNumber(Question $question): Question
    {
        $counter = 1;
        $quiz = $question->getQuiz();
        $questions = $this->getQuestionsByQuiz($quiz);
        if (!empty($questions)) {
            $counter = $this->fixQuizNumbers($quiz);
        }
        $question->setNumber($counter);
        $question->generateSlug();
        return $question;
    }

    public function fixQuizNumbers(Quiz $quiz): int
    {
        $counter = 1;
        $questions = $this->getQuestionsByQuiz($quiz);
        if (!empty($questions)) {
            foreach ($questions as $item) {
                if ($item instanceof Question) {
                    $number = $item->getNumber();
                    if ($number !== $counter) {
                        $item->setNumber($counter);
                        $item->generateSlug();
                        $this->em->persist($item);
                    }
                    $counter++;
                }
            }
        }
        $this->em->flush();
        return $counter;
    }

    public function getQuestionsByQuiz(Quiz $quiz): ?array
    {
        return $this->em->getRepository(Question::class)->findBy(['quiz' => $quiz], ['number' =>  'ASC']);
    }
}
