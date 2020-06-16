<?php declare(strict_types=1);

namespace App\Command;

use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ApiResource(
 *     messenger=true,
 *     collectionOperations={
 *         "post"={"status"=202}
 *     },
 *     itemOperations={},
 *     output=false
 * )
 */
class QuizStartCommand
{
    private string $quizIri;
    public function setQuizIri(string $quizIri): self
    {
        $this->quizIri = $quizIri;
        return $this;
    }

    public function getQuizIri(): string
    {
        return $this->quizIri;
    }
}
