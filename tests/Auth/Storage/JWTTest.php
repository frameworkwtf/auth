<?php

declare(strict_types=1);

namespace Wtf\Auth\Tests\Auth\Storage;

use PHPUnit\Framework\TestCase;

class JWTTest extends TestCase
{
    protected $app;

    protected function setUp(): void
    {
        $dir = __DIR__.'/../../data/config.jwt';
        $this->app = new \Wtf\App(['config_dir' => $dir]);
        unset($this->app->getContainer()['entity']);
        $container = $this->app->getContainer();

        $this->app->getContainer()['entity'] = $this->app->getContainer()->protect(function ($name) use ($container) {
            return new \Wtf\Auth\Tests\Dummy\Dummy($container);
        });
    }

    public function testLoggedIn(): void
    {
        $this->assertFalse($this->app->getContainer()->auth->isLoggedIn());
    }

    public function testGetUserNull(): void
    {
        $this->assertNull($this->app->getContainer()->auth->getUser());
    }

    public function testGetUserFromContainer(): void
    {
        $this->app->getContainer()['user'] = ['login' => 'login'];

        $this->assertInstanceOf('\Wtf\Root', $this->app->getContainer()->auth->getUser());
        $this->assertEquals('login', $this->app->getContainer()->auth->getUser()->get('login'));
    }

    public function testGetUserFromRequest(): void
    {
        $request = $this->app->getContainer()['request'];
        unset($this->app->getContainer()['request']);
        $this->app->getContainer()['request'] = $request->withAttribute('token', ['data' => ['login' => 'login']]);
        $this->assertInstanceOf('\Wtf\Root', $this->app->getContainer()->auth->getUser());
        $this->assertEquals('login', $this->app->getContainer()->auth->getUser()->get('login'));
    }

    public function testLogin(): void
    {
        $this->assertNull($this->app->getContainer()->auth->login('notexist', 'password'));
        $this->assertNull($this->app->getContainer()->auth->login('login', 'wrongpassword'));
        $this->assertInternalType('string', $this->app->getContainer()->auth->login('login', 'me'));
    }

    public function testLogout(): void
    {
        $this->assertNull($this->app->getContainer()->auth->logout());
    }
}
