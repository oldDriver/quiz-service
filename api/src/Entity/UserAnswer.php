<?php
namespace App\Entity;

class UserAnswer
{
    private Question $question;
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