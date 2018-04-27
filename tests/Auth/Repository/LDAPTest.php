<?php

declare(strict_types=1);

namespace Wtf\Auth\Tests\Auth\Storage;

use PHPUnit\Framework\TestCase;

class LDAPTest extends TestCase
{
    protected $app;

    protected function setUp(): void
    {
        $dir = __DIR__.'/../../data/config.ldap';
        $this->app = new \Wtf\App(['config_dir' => $dir]);
        unset($this->app->getContainer()['entity']);
        unset($this->app->getContainer()['ldap_client']);
        $container = $this->app->getContainer();

        $this->app->getContainer()['entity'] = $this->app->getContainer()->protect(function ($name) use ($container) {
            return new \Wtf\Auth\Tests\Dummy\LDAPEntity($container);
        });
        $this->app->getContainer()['ldap_client'] = function ($c) {
            return new \Wtf\Auth\Tests\Dummy\LDAPClient($c);
        };
    }

    public function testGetByLogin(): void
    {
        $this->assertNull($this->app->getContainer()->auth_repository->getByLogin('not.exists'));
        $this->assertInstanceOf('\Wtf\Root', $this->app->getContainer()->auth_repository->getByLogin('exists'));
    }

    public function testLoginInvalid(): void
    {
        $this->assertNull($this->app->getContainer()->auth_repository->login('not.exists', 'invalid'));
        $this->assertNull($this->app->getContainer()->auth_repository->login('exists', 'invalid'));
    }

    public function testLogin(): void
    {
        $this->assertInstanceOf('\Wtf\Root', $this->app->getContainer()->auth_repository->login('exists', 'valid'));
    }

    public function testUniplemented(): void
    {
        $this->assertInternalType('string', $this->app->getContainer()->auth_repository->forgot('nevermind'));
        $this->assertFalse($this->app->getContainer()->auth_repository->reset('nevermind', 'nevermind'));
    }
}
