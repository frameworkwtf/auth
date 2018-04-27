<?php

declare(strict_types=1);

namespace Wtf\Auth\Repository;

use Psr\Container\ContainerInterface;
use Wtf\Root;

class LDAP extends Root implements RepositoryInterface
{
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        //@codeCoverageIgnoreStart
        if (!\class_exists('\Symfony\Component\Ldap\Ldap')) {
            throw new \Exception('symfony/ldap package required for ldap auth');
        }
        //@codeCoverageIgnoreEnd
    }

    /**
     * {@inheritdoc}
     */
    public function getLoginFields(): array
    {
        return $this->config('auth.ldap.fields.login', ['uid', 'mail']);
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

        try {
            $this->ldap_client->bind($user->get($this->config('auth.ldap.fields.loginInDb', 'email')), $password);

            return $user;
        } catch (\Throwable $t) {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getByLogin(string $login): ?Root
    {
        $query = '(|';
        foreach ($this->getLoginFields() as $field) {
            $query .= '('.$field.'='.$login.')';
        }
        $query .= ')';
        $collection = $this->ldap_client
                           ->query($this->config('auth.ldap.baseDN'), $query)
                           ->execute();

        foreach ($collection->toArray() as $entry) {
            $user = $this->entity($this->config('auth.entity'))->load($entry->getDn(), $this->config('auth.ldap.fields.loginInDb', 'email'));
            foreach ($entry->getAttributes() as $attribute => $value) {
                $field = $this->config('auth.fields.map.'.$attribute);
                if ($field) {
                    $user->set($field, $value[0] ?? null);
                }
            }
            $user->save();

            return $user;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function forgot(string $login): string
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function reset(string $code, string $new_password): bool
    {
        return false;
    }
}
