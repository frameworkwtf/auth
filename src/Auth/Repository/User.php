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
     * {@inheritdoc}
     */
    public function login(string $login, string $password): ?Root
    {
        $user = $this->getByLogin($login);
        if (null === $user) {
            return null;
        }

        if (!\password_verify($password, $user->get($this->getPasswordField()))) {
            return null;
        }

        return $user;
    }

    /**
     * {@inheritdoc}
     */
    public function getByLogin(string $login): ?Root
    {
        $entity = $this->entity($this->config('auth.entity', 'user'));
        foreach ($this->getLoginFields() as $field) {
            try {
                if ($entity->has([$field => $login])) {
                    return $entity->load($login, $field);
                }
            } catch (\Throwable $t) {
                //If field does not exist, exception will be thrown,
                //but for that case it's not a problem,
                //so just ignore it and go to the next field
            }
        }

        return null;
    }
}
