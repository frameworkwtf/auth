<?php

declare(strict_types=1);

namespace Wtf\Auth\Repository;

use Wtf\Root;

class User extends Root implements RepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getLoginFields(): array
    {
        return ['email', 'login', 'username'];
    }

    /**
     * Get password field, eg: 'password'.
     *
     * @return string
     */
    public function getPasswordField(): string
    {
        return 'password';
    }

    /**
     * Hash password.
     *
     * @return string
     */
    public function hashPassword(string $password): string
    {
        return password_hash($password, $this->config('auth.password.algo', PASSWORD_DEFAULT), $this->config('auth.password.options', []));
    }

    /**
     * {@inheritdoc}
     */
    public function login(string $login, string $password): ?Root
    {
        $user = $this->getByLogin($login);
        if (null === $user) {
            return null;
        }

        if (!password_verify($password, $this->hashPassword($password))) {
            return null;
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function getByLogin(string $login): ?RepositoryInterface
    {
        $entity = $this->entity($this->config('auth.entity', 'user'));
        foreach ($this->getLoginFields() as $field) {
            try {
                if ($entity->has([$field => $login])) {
                    $this->data = $entity->getData();

                    return $this;
                }
            } catch (\Throwable $t) {
                //If field does not exist, exception will be thrown,
                //but for that case it's not a problem,
                //so just ignore it and go to the next field
            }
        }

        return $null;
    }
}
