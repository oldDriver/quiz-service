<?php declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Carbon\Carbon;



/**
 * @ApiResource(
 *      subresourceOperations={
 *          "api_quizzes_results_get_subresource"= {
 *              "security"="is_granted('ROLE_USER')"
 *          }
 *      }

 * )
 * @ORM\Entity(repositoryClass="App\Repository\ResultRepository")
 *
 */
class Result
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * 
     * @ORM\Column(type="bigint")
     */
    private ?int $userId = null;

    /**
     * @ORM\ManyToOne(targetEntity="Quiz", inversedBy="results")
     * @ORM\JoinColumn(name="quiz_id", referencedColumnName="id")
     */
    private Quiz $quiz;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private string $result;

    /**
     * 
     * @ORM\Column(type="float")
     */
    private float $score = 0;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private Carbon $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private Carbon $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): self
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

