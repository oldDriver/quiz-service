<?php declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Carbon\Carbon;
use App\Annotation\UserAware;
use App\Dto\AnswerInput;
use App\Dto\AnswerOutput;
use App\Dto\QuizStart;
use App\Dto\ResultOutput;
use Symfony\Component\Serializer\Annotation\Groups;




/**
 * @ApiResource(
 *      collectionOperations={
 *          "get"={
 *              "security"="is_granted('ROLE_USER')"
 *          },
 *          "start"={
 *              "security"="is_granted('ROLE_USER')",
 *              "method"="POST",
 *              "path"="results",
 *              "input"=QuizStart::class,
 *              "output"=ResultOutput::class,
 *              "messenger"="input",
 *              "normalization_context"={"groups"={"result:read"}},
 *          }
 *      },
 *      subresourceOperations={
 *          "api_quizzes_results_get_subresource"= {
 *              "security"="is_granted('ROLE_USER')"
 *          }
 *      }
 * )
 * @UserAware(userFieldName="user_id")
 * @ORM\Entity(repositoryClass="App\Repository\ResultRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Result
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"result:read"})
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="bigint")
     * @Assert\NotNull
     * @Groups({
     *      "result:start"
     * })
     */
    private ?int $userId = null;

    /**
     * @ORM\ManyToOne(targetEntity="Quiz", inversedBy="results")
     * @ORM\JoinColumn(name="quiz_id", referencedColumnName="id")
     * @Assert\NotNull
     * @Groups({"result:start"})
     */
    private ?Quiz $quiz = null;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private array $result= [];

    /**
     * 
     * @ORM\Column(type="float")
     */
    private float $score = 0;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?Carbon $createdAt = null;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?Carbon $updatedAt = null;

    /**
     * @ORM\PrePersist
     */
    public function generateCreatedAt(): self
    {
        if ($this->createdAt === null) {
            $this->createdAt = Carbon::now();
        }
        return $this;
    }

    /**
     * @ORM\PreUpdate
     */
    public function generateUpdatedAt(): self
    {
        $this->updatedAt = Carbon::now();
        return $this;
    }
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getResult(): ?array
    {
        return $this->result;
    }

    public function setResult(?array $result): self
    {
        $this->result = $result;

        return $this;
    }

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function setScore(float $score): self
    {
        $this->score = $score;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

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
    
}

