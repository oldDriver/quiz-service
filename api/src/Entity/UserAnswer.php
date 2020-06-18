<?php
namespace App\Entity;

use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Serializer\Annotation\Groups;

class UserAnswer
{
    /**
     * @MaxDepth(1)
     * @Groups({"user:answer"})
     */
    private Question $question;
    /**
     * @MaxDepth(1)
     * @Groups({"user:answer"})
     */
    private Answer $answer;

    public function setQuestion(Question $question): self
    {
        $this->question = $question;
        return $this;
    }

    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function setAnswer(Answer $answer): self
    {
        $this->answer = $answer;
        return $this;
    }

    public function getAnswer(): Answer
    {
        return $this->answer;
    }
}