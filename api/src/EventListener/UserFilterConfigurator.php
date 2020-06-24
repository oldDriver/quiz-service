<?php
namespace App\EventListener;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Annotations\Reader;

final class UserFilterConfigurator
{
    private EntityManagerInterface $em;
    private TokenStorageInterface $tokenStorage;
    private Reader $reader;

    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage, Reader $reader)
    {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
        $this->reader = $reader;
    }

    public function onKernelRequest(): void
    {
        if ($user = $this->getUser()) {
            if (!in_array('ROLE_ADMIN', $user->getRoles())) {
                $filter = $this->em->getFilters()->enable('user_filter');
                $filter->setParameter('id', $user->getId());
                $filter->setAnnotationReader($this->reader);
            }
        }
    }

    private function getUser(): ?UserInterface
    {
        if (!$token = $this->tokenStorage->getToken()) {
            return null;
        }
        
        $user = $token->getUser();
        return $user instanceof UserInterface ? $user : null;
    }
}
