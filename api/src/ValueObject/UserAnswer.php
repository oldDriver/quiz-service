<?php
namespace App\ValueObject;

use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Question;
use App\Entity\Answer;
use Symfony\Component\Validator\Constraints as Assert;

class UserAnswer
{
    /**
     * @MaxDepth(1)
     * @Groups({"user:answer"})
     * @Assert\NotNull
     */
    private ?Question $question = null;
    /**
     * @MaxDepth(1)
     * @Groups({"user:answer"})
     * @Assert\NotNull
     */
    private ?Answer $answer = null;

    public function setQuestion(Question $question): self
    {
        $this->question = $question;
        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setAnswer(Answer $answer): self
    {
        $this->answer = $answer;
        return $this;
    }

    public function getAnswer(): ?Answer
    {
        return $this->answer;
    }
}
