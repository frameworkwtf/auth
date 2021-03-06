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
        if (PHP_SESSION_ACTIVE !== \session_status()) {
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
        if (PHP_SESSION_ACTIVE !== \session_status()) {
            return null;
        }

        if ($_SESSION['user'] ?? null) {
            $entity = $this->entity($this->config('auth.entity'));
            if ($entity->has(['id' => $_SESSION['user']['id']])) {
                return $entity->load($_SESSION['user']['id']);
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function isLoggedIn(): bool
    {
        if (PHP_SESSION_ACTIVE !== \session_status()) {
            return false;
        }

        return (bool) ($_SESSION['user'] ?? null);
    }

    /**
     * {@inheritdoc}
     */
    public function logout(): void
    {
        $_SESSION = [];
        if (PHP_SESSION_ACTIVE === \session_status()) {
            \session_destroy();
        }
    }
}
