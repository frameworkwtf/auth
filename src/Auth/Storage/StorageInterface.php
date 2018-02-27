<?php

declare(strict_types=1);

namespace Wtf\Auth\Storage;

use Wtf\Root;

interface StorageInterface
{
    /**
     * Set user data to storage.
     *
     * @param Root $user
     *
     * @return mixed
     */
    public function setUser(Root $user);

    /**
     * Get current user from storage.
     *
     * @param mixed $storage Custom storage, eg: jwt token string, default: null
     *
     * @return null|Root
     */
    public function getUser($storage = null): ?Root;

    /**
     * Check if current user logged in.
     *
     * @return bool
     */
    public function isLoggedIn(): bool;
}
