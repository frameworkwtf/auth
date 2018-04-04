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

        if ($this->container->has('user')) {
            unset($this->container['user']);
        }
        $this->container['user'] = $user;

        return $this->auth_storage->setUser($user);
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

    public function logout(): void
    {
        $this->auth_storage->logout();
    }
}
