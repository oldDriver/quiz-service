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
        $questions = $this->em->getRepository(Question::class)->findBy(['quiz' => $question->getQuiz()], ['number' =>  'ASC']);
        if (!empty($questions)) {
            $counter = 1;
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
            $question->setNumber($counter);
            $question->generateSlug();
        }
        return $question;
    }

    public function fixQuizNumbers(Quiz $quiz): void
    {
        $questions = $this->em->getRepository(Question::class)->findBy(['quiz' => $quiz], ['number' =>  'ASC']);
        if (!empty($questions)) {
            $counter = 1;
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
    }
}
