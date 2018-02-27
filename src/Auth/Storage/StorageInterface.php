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
     */
    public function setUser(Root $user);

    /**
     * Get current user from storage.
     *
     * @return null|Root
     */
    public function getUser(): ?Root;

    /**
     * Check if current user logged in.
     *
     * @return bool
     */
    public function isLoggedIn(): bool;
}
