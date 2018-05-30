<?php

declare(strict_types=1);

namespace Wtf\Auth\Tests\Dummy;

class LDAPEntity extends \Wtf\Root
{
    protected $data = [
        'id' => 1,
        'email' => 'cn=exists,cn=Users,dc=framework,dc=wtf',
    ];

    public function __construct($container)
    {
        parent::__construct($container);
        if ($container->has('forgot')) {
            $this->set('forgot', $container->get('forgot'));
        }
    }

    public function load($value, $field = 'id', $fields = '*')
    {
        return $this;
    }

    public function has($where)
    {
        if (
            ('email' === \array_keys($where)[0] && 'cn=Nikita Chernyi,cn=Users,dc=titanium-soft,dc=com' === $where['email'])
            || ('id' === \array_keys($where)[0] && '1' === $where['id'])
            || ('forgot' === \array_keys($where)[0] && 'notexists' !== $where['forgot'])
        ) {
            return true;
        }

        return false;
    }

    public function save(bool $validate = true): \Wtf\Root
    {
        return $this;
    }
}
