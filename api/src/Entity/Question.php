<?php declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use App\Helper\StringHelper;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *      collectionOperations={
 *          "post"={
 *              "security"="is_granted('ROLE_EDITOR')"
 *          }
 *      },
 *      itemOperations={
 *          "get"={
 *              "normalization_context"={"groups"={"question:read"}},
 *          },
 *          "patch"={
 *              "security"="is_granted('ROLE_EDITOR')"
 *          },
 *          "delete"={
 *              "security"="is_granted('ROLE_ADMIN')"
 *          }
 *      },
 *      attributes={
 *         "normalization_context"={"enable_max_depth"=true},
 *     }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Question
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"user:answer"})
     */
    private ?int $id = null;

    /**
     * @ORM\ManyToOne(targetEntity="Quiz", inversedBy="questions")
     * @ORM\JoinColumn(name="quiz_id", referencedColumnName="id")
     * @Assert\NotNull
     */
    private ?Quiz $quiz = null;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"user:answer"})
     */
    private int $number = 1;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     * @Groups({
     *      "question:read",
     *      "user:answer"
     * })
     */
    private string $question = '';

    /**

     * @ORM\Column(type="string", nullable=true)
     */
    private string $slug = '';

    /**
     * @ORM\OneToMany(targetEntity="Answer", mappedBy="question", cascade={"remove"})
     * @ApiSubresource
     * @Groups({
     *      "question:read",
     *      "user:answer"
     * })
     */
    private $answers;

    public function __construct()
    {
        $this->answers = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     */
    public function generateSlug(): self
    {
        $this->slug = StringHelper::Slugify(implode(' ', [$this->getQuiz()->getName(), $this->number]));
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(?Quiz $quiz): self
    {
        $this->quiz = $quiz;

        return $this;
    }

    /**
     * @return Collection|Answer[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(Answer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->contains($answer)) {
            $this->answers->removeElement($answer);
            // set the owning side to null (unless already changed)
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }

        return $this;
    }
}
