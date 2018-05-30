<?php

declare(strict_types=1);

namespace Wtf\Auth\Tests\Dummy;

class Dummy extends \Wtf\Root
{
    protected $data = [
        'id' => 1,
        'login' => 'login',
        'password' => '$2y$10$W4LwVSVpKxZqXelwwcV92ORTZGIodZRK8c1o4VW84sPExYRXfSUL6', //me
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
            ('login' === \array_keys($where)[0] && 'login' === $where['login'])
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
