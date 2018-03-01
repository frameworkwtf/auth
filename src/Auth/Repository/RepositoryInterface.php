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
    public function getByLogin(string $login): ?self;

    /**
     * Get login fields, eg: ['email', 'username'].
     *
     * @return array
     */
    public function getLoginFields(): array;

    /**
     * Check if provided login and password are correct and return matched user
     * Otherwise, return null if no user found or password incorrect.
     *
     * @return null|Root
     */
    public function login(string $login, string $password): ?Root;
}
