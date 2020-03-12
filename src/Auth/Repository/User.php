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
     */
    public function getPasswordField(): string
    {
        return 'password';
    }

    /**
     * Get forgot password code field, eg: 'forgot'.
     */
    public function getForgotField(): string
    {
        return 'forgot';
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
                // @codeCoverageIgnoreStart
            } catch (\Throwable $t) {
                //If field does not exist, exception will be thrown,
                //but for that case it's not a problem,
                //so just ignore it and go to the next field
            }
            // @codeCoverageIgnoreEnd
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function forgot(string $login): string
    {
        $user = $this->getByLogin($login);
        if (null === $user) {
            return '';
        }

        $user->set($this->getForgotField(), \md5($user->getId().\random_int(PHP_INT_MIN, PHP_INT_MAX)))->save(false);

        return $user->get($this->getForgotField());
    }

    /**
     * {@inheritdoc}
     */
    public function reset(string $code, string $new_password): bool
    {
        $user = $this->entity($this->config('auth.entity', 'user'));
        if (!$user->has([$this->getForgotField() => $code])) {
            return false;
        }

        $user->load($code, $this->getForgotField())
             ->setData([
                 $this->getForgotField() => null,
                 $this->getPasswordField() => \password_hash($new_password, PASSWORD_DEFAULT),
             ])->save(false);

        return true;
    }
}
