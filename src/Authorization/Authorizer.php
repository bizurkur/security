<?php

namespace Bitty\Security\Authorization;

use Bitty\Security\Authorization\AuthorizerInterface;
use Bitty\Security\Exception\AuthorizationException;
use Bitty\Security\User\UserInterface;

class Authorizer implements AuthorizerInterface
{
    /**
     * {@inheritDoc}
     */
    public function authorize(UserInterface $user, array $roles): bool
    {
        // This is not done
        return true;
    }
}
