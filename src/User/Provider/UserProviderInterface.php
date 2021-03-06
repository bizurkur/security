<?php

namespace Bitty\Security\User\Provider;

use Bitty\Security\User\UserInterface;

interface UserProviderInterface
{
    /**
     * @var int
     */
    public const MAX_USERNAME_LEN = 4096;

    /**
     * Gets the user.
     *
     * @param string $username
     *
     * @return UserInterface|null
     */
    public function getUser(string $username): ?UserInterface;
}
