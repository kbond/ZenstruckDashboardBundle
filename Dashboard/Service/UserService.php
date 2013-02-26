<?php

namespace Zenstruck\Bundle\DashboardBundle\Dashboard\Service;

use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * @author Kevin Bond <kevinbond@gmail.com>
 */
class UserService
{
    protected $securityContext;

    public function __construct(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    public function __toString()
    {
        return (string) $this->getUser();
    }

    public function getUser()
    {
        $token = $this->securityContext->getToken();

        if (!$token) {
            return 'anon.';
        }

        return $token->getUser();
    }
}