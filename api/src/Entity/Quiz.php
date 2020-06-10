<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Carbon\Carbon;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\ArrayCollection;
use App\Helper\StringHelper;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 *
 * @ApiResource(
 *      attributes={
 *          "normalization_context"={"groups"={"quiz:questions"}}
 *      },
 *      collectionOperations={
 *          "get",
 *          "post"={
 *              "security"="is_granted('ROLE_EDITOR')"
 *          }
 *      },
 *      itemOperations={
 *          "get",
 *          "patch"={
 *              "security"="is_granted('ROLE_EDITOR')"
 *          },
 *          "delete"={
 *              "security"="is_granted('ROLE_ADMIN')"
 *          }
 *      }
 * )
 * @ApiFilter(SearchFilter::class, properties={"slug": "partial"})
 * @ORM\Entity(repositoryClass="App\Repository\QuizRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("name")
 */
class Quiz
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @Groups({
     *      "quiz:questions"
     * })
     * @ORM\Column
     * @Assert\NotBlank
     */
    private string $name = '';

    /**
     * @Groups({
     *      "quiz:questions"
     * })
     * @ORM\Column(type="text", nullable=true)
     */
    private string $description = '';

    /**
     * 
     * @ORM\Column(type="string", nullable=true)
     */
    private string $slug = '';

    /**
     * @Groups({
     *      "quiz:questions"
     * })
     * @ORM\OneToMany(targetEntity="Question", mappedBy="quiz")
     * @ApiSubresource(maxDepth=1)
     */
    private $questions;

    /**
     *
     * @ORM\OneToMany(targetEntity="Result", mappedBy="quiz")
     * @ApiSubresource
     */
    private $results;
    
    
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?Carbon $createdAt = null;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->results = new ArrayCollection();
    }

    /**
     * @ORM\PrePersist
     */
    public function generateSlug(): self
    {
        if ($this->createdAt === null) {
            $this->createdAt = Carbon::now();
        }
        $this->slug = StringHelper::Slugify($this->name);
        return $this;
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setQuiz($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getQuiz() === $this) {
                $question->setQuiz(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Result[]
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    public function addResult(Result $result): self
    {
        if (!$this->results->contains($result)) {
            $this->results[] = $result;
            $result->setQuiz($this);
        }

        return $this;
    }

    public function removeResult(Result $result): self
    {
        if ($this->results->contains($result)) {
            $this->results->removeElement($result);
            // set the owning side to null (unless already changed)
            if ($result->getQuiz() === $this) {
                $result->setQuiz(null);
            }
        }

        return $this;
    }
}
