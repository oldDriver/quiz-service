<?php declare(strict_types=1);


namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Helper\StringHelper;
use Symfony\Component\Serializer\Annotation\Groups;



/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\AnswerRepository")
 */
class Answer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity="Question", inversedBy="answers")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     * @Assert\NotNull
     */
    private ?Question $question = null;

    /**
     * @ORM\Column(type="text")
     * @Groups({
     *      "question:read",
     *      "user:answer"
     * })
     * @Assert\NotBlank
     */
    private string $answer = '';

    /**
     * @ORM\Column(type="boolean", nullable=true)
     * @Groups({"user:answer"})
     * @Assert\NotNull
     */
    private ?bool $isRight = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getIsRight(): ?bool
    {
        return $this->isRight;
    }

    public function setIsRight(?bool $isRight): self
    {
        $this->isRight = $isRight;

        return $this;
    }
    
    
}

