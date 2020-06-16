<?php declare(strict_types=1);

namespace App\Handler;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Command\QuizStartCommand;
use ApiPlatform\Core\Api\IriConverterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Entity\User;
use App\Service\ResultService;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Result;

class QuizStartCommandHandler implements MessageHandlerInterface
{
    private TokenStorageInterface $tokenStorage;
    private IriConverterInterface $iriConverter;
    private ResultService $resultService;
    private EntityManagerInterface $em;
    public function __construct(
        TokenStorageInterface $tokenStorage,
        IriConverterInterface $iriConverter,
        ResultService $resultService,
        EntityManagerInterface $em
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->iriConverter = $iriConverter;
        $this->resultService = $resultService;
        $this->em = $em;
    }
    public function __invoke(QuizStartCommand $quizStartRequest)
    {
        if (null === $this->tokenStorage->getToken() || !$this->tokenStorage->getToken()->getUser() instanceof User) {
            throw new \Exception();
        }
        $user = $this->tokenStorage->getToken()->getUser();
        $quiz = $this->iriConverter->getItemFromIri($quizStartRequest->getQuizIri());
        $result = $this->em->getRepository(Result::class)->findOneBy(['quiz' => $quiz, 'userId' => $user->getId()]);
        if (empty($result)) {
            $result = $this->resultService->startQuiz($user, $quiz);
        }
        return $result;
    }
}