<?php

declare(strict_types=1);

namespace Wtf\Auth\Storage;

use Wtf\Root;

interface StorageInterface
{
    /**
     * Set user data to storage.
     *
     * @return mixed
     */
    public function setUser(Root $user);

    /**
     * Get current user from storage.
     */
    public function getUser(): ?Root;

    /**
     * Check if current user logged in.
     */
    public function isLoggedIn(): bool;

    /**
     * Log out current user.
     */
    public function logout(): void;
}
