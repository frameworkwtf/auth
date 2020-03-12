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
     * Generate special code for user who forgot password.
     *
     * @return string code
     */
    public function forgot(string $login): string
    {
        return $this->auth_repository->forgot($login);
    }

    /**
     * Reset user password by code.
     *
     * @param string $code         Return value of self::forgot()
     * @param string $new_password New password for user
     */
    public function reset(string $code, string $new_password): bool
    {
        return $this->auth_repository->reset($code, $new_password);
    }

    /**
     * Check if current user is logged in.
     */
    public function isLoggedIn(): bool
    {
        return $this->auth_storage->isLoggedIn();
    }

    /**
     * Get current user.
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
