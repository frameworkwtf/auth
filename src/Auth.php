<?php

declare(strict_types=1);

namespace Wtf;

class Auth extends Root
{
    /**
     * Log in user.
     *
     * Return result, based by selected session storage
     *
     * @param string $login
     * @param string $password
     *
     * @return mixed
     */
    public function login(string $login, string $password)
    {
        $user = $this->auth_repository->login($login, $password);

        if (null === $user) {
            return null;
        }

        return $this->auth_storage->setUser($user);
    }

    /**
     * Check if current user is logged in.
     *
     * @param mixed $storage custom storage, for JWT, Session, etc
     *
     * @return bool
     */
    public function isLoggedIn($storage = null): bool
    {
        return $this->auth_storage->isLoggedIn($storage);
    }

    /**
     * Get current user.
     *
     * @param mixed $storage custom storage, for JWT, Session, etc
     *
     * @return null|Root
     */
    public function getUser($storage = null): ?Root
    {
        return $this->auth_storage->getUser($storage);
    }
}
