<?php

declare(strict_types=1);

namespace Wtf\Auth\Storage;

use Wtf\Root;

class Session extends Root implements StorageInterface
{
    /**
     * {@inheritdoc}
     */
    public function setUser(Root $user)
    {
        if (PHP_SESSION_ACTIVE !== session_status()) {
            throw new \Exception('Session not started');
        }

        $_SESSION['user'] = $user->getData();
        if (isset($_SESSION['user']['password'])) {
            unset($_SESSION['user']['password']);
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser(): ?Root
    {
        if (PHP_SESSION_ACTIVE !== session_status()) {
            return null;
        }

        if ($_SESSION['user'] ?? null) {
            return $this->entity($this->config('auth.entity'))->setData($_SESSION['user']);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function isLoggedIn(): bool
    {
        if (PHP_SESSION_ACTIVE !== session_status()) {
            return false;
        }

        return (bool) ($_SESSION['user'] ?? null);
    }
}
