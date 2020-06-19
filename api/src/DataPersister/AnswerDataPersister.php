<?php
namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use ApiPlatform\Core\Api\IriConverterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Service\ResultService;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Dto\AnswerInput;
use Symfony\Component\VarDumper\VarDumper;
use App\Entity\Result;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;


class AnswerDataPersister implements ContextAwareDataPersisterInterface
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
    
    public function supports($data, array $context = []): bool
    {
        return $data instanceof AnswerInput;
    }
    
    public function persist($data, array $context = [])
    {
        // call your persistence layer to save $data
        if (null === $this->tokenStorage->getToken() || !$this->tokenStorage->getToken()->getUser() instanceof User) {
            throw new HttpException(Response::HTTP_FORBIDDEN);
        }
        $user = $this->tokenStorage->getToken()->getUser();
        $quiz = $this->iriConverter->getItemFromIri($data->quizIri);
        $question = $this->iriConverter->getItemFromIri($data->questionIri);
        $answer = $this->iriConverter->getItemFromIri($data->answerIri);
        $result = $this->em->getRepository(Result::class)->findOneBy(['quiz' => $quiz, 'userId' => $user->getId()]);
        if (empty($result)) {
            throw new HttpException(Response::HTTP_NOT_FOUND);
        }
        $result = $this->resultService->addAnswer($user, $result, $question, $answer);
        if (empty($result)) {
            throw new HttpException(Response::HTTP_BAD_REQUEST);
        }
        return $result;
    }
    
    public function remove($data, array $context = [])
    {
        return false;
    }
}