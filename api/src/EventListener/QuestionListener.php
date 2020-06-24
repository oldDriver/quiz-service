<?php declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Question;
use App\Service\QuestionService;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class QuestionListener
{
    private QuestionService $questionService;

    public function __construct(QuestionService $questionService)
    {
        $this->questionService = $questionService;
    }
    public function prePersist(Question $question, LifecycleEventArgs $args): void
    {
        $question = $this->questionService->setNumber($question);
    }

    public function postRemove(Question $question, LifecycleEventArgs $args): void
    {
       $this->questionService->fixQuizNumbers($question->getQuiz());
    }
}
