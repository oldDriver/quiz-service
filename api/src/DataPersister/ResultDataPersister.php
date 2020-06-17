<?php
namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Result;
use ApiPlatform\Core\Api\IriConverterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Service\ResultService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\VarDumper\VarDumper;
use App\Dto\QuizStart;
use App\Entity\User;


class ResultDataPersister implements ContextAwareDataPersisterInterface
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
        return $data instanceof QuizStart;
    }
    
    public function persist($data, array $context = [])
    {
        // call your persistence layer to save $data
        if (null === $this->tokenStorage->getToken() || !$this->tokenStorage->getToken()->getUser() instanceof User) {
            throw new \Exception();
        }
        //VarDumper::dump($data); exit;
        $user = $this->tokenStorage->getToken()->getUser();
        $quiz = $this->iriConverter->getItemFromIri($data->quizIri);
        
        $result = $this->em->getRepository(Result::class)->findOneBy(['quiz' => $quiz, 'userId' => $user->getId()]);
        if (empty($result)) {
            $result = $this->resultService->startQuiz($user, $quiz);
        }
        return $data;
    }
    
    public function remove($data, array $context = [])
    {
        return false;
        // call your persistence layer to delete $data
    }
}