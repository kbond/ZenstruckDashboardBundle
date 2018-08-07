<?php

namespace Zenstruck\Bundle\DashboardBundle\Dashboard\Service;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class UserService
{
    protected $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    public function __toString()
    {
        return (string) $this->getUser();
    }

    public function getUser()
    {
        $token = $this->tokenStorage->getToken();

        if (!$token) {
            return 'anon.';
        }

        return $token->getUser();
    }
}
