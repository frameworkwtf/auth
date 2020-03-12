<?php

declare(strict_types=1);

namespace Wtf\Auth\Repository;

use Wtf\Root;

interface RepositoryInterface
{
    /**
     * Get user by login.
     *
     * @return null|RepositoryInterface
     */
    public function getByLogin(string $login): ?Root;

    /**
     * Get login fields, eg: ['email', 'username'].
     */
    public function getLoginFields(): array;

    /**
     * Check if provided login and password are correct and return matched user
     * Otherwise, return null if no user found or password incorrect.
     */
    public function login(string $login, string $password): ?Root;

    /**
     * Generate special code for user who forgot password.
     *
     * @return string code
     */
    public function forgot(string $login): string;

    /**
     * Reset user password by code.
     *
     * @param string $code         Return value of self::forgot()
     * @param string $new_password New password for user
     */
    public function reset(string $code, string $new_password): bool;
}
