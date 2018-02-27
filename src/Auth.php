<?php

declare(strict_types=1);

namespace Wtf;

class Auth extends Root
{
    /**
     * Log in user.
     *
     * @param string $login
     * @param string $password
     *
     * @return null|Root
     */
    public function login(string $login, string $password): ?Root
    {
        $user = $this->auth_repository->login($login, $password);

        if (null === $user) {
            return null;
        }

        $this->auth_storage->setUser($user);

        return $user;
    }

    /**
     * Check if current user is logged in.
     *
     * @return bool
     */
    public function isLoggedIn(): bool
    {
        return $this->auth_storage->isLoggedIn();
    }

    /**
     * Get current user.
     *
     * @return null|Root
     */
    public function getUser(): ?Root
    {
        return $this->auth_storage->getUser();
    }
}
