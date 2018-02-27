<?php

declare(strict_types=1);

namespace Wtf\Auth\Storage;

use Wtf\Root;

class JWT extends Root implements StorageInterface
{
    /**
     * {@inheritdoc}
     */
    public function setUser(Root $user): void
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getUser(): ?Root
    {
    }

    /**
     * {@inheritdoc}
     */
    public function isLoggedIn(): bool
    {
    }
}
