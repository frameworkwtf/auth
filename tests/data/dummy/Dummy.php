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

    public function setData($data)
    {
        $this->data = array_merge($this->data, $data);

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function load($value, $field = 'id', $fields = '*')
    {
        return $this;
    }

    public function has($where)
    {
        if (
            ('login' === array_keys($where)[0] && 'login' === $where['login'])
            || ('id' === array_keys($where)[0] && 1 === $where['id'])
        ) {
            return true;
        }

        throw new \Exception('test exception with not existing field');
    }
}
