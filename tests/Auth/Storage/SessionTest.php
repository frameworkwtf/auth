<?php

declare(strict_types=1);

namespace Wtf\Auth\Tests\Auth\Storage;

use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{
    protected $app;

    protected function setUp(): void
    {
        $dir = __DIR__.'/../../data/config.session';
        $this->app = new \Wtf\App(['config_dir' => $dir]);
        unset($this->app->getContainer()['entity']);
        $container = $this->app->getContainer();

        $this->app->getContainer()['entity'] = $this->app->getContainer()->protect(function ($name) use ($container) {
            return new \Wtf\Auth\Tests\Dummy\Dummy($container);
        });
    }

    public function testLoggedInWithoutSession(): void
    {
        $this->assertFalse($this->app->getContainer()->auth->isLoggedIn());
    }

    public function testGetUserNullWithoutSession(): void
    {
        $this->assertNull($this->app->getContainer()->auth->getUser());
    }

    public function testGetUserWithoutSession(): void
    {
        $this->assertNull($this->app->getContainer()->auth->getUser());
    }

    public function testLoginWithoutSession(): void
    {
        $this->assertNull($this->app->getContainer()->auth->login('notexist', 'password'));
        $this->assertNull($this->app->getContainer()->auth->login('login', 'wrongpassword'));
        $this->expectException(\Exception::class);
        $this->assertInstanceOf(null, $this->app->getContainer()->auth->login('login', 'me'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testLoggedIn(): void
    {
        \session_start();
        $this->assertFalse($this->app->getContainer()->auth->isLoggedIn());
    }

    /**
     * @runInSeparateProcess
     */
    public function testGetUserNull(): void
    {
        \session_start();
        $this->assertNull($this->app->getContainer()->auth->getUser());
    }

    /**
     * @runInSeparateProcess
     */
    public function testGetUser(): void
    {
        \session_start();
        $_SESSION['user'] = ['id' => '1', 'login' => 'login'];

        $this->assertInstanceOf('\Wtf\Root', $this->app->getContainer()->auth->getUser());
        $this->assertEquals('login', $this->app->getContainer()->auth->getUser()->get('login'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testLogin(): void
    {
        \session_start();
        $this->assertNull($this->app->getContainer()->auth->login('notexist', 'password'));
        $this->assertNull($this->app->getContainer()->auth->login('login', 'wrongpassword'));
        $this->assertInstanceOf('\Wtf\Root', $this->app->getContainer()->auth->login('login', 'me'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testLogout(): void
    {
        \session_start();
        $_SESSION['user'] = ['id' => '1', 'login' => 'login'];
        $this->assertInstanceOf('\Wtf\Root', $this->app->getContainer()->auth->getUser());
        $this->app->getContainer()->auth->logout();
        $this->assertNull($this->app->getContainer()->auth->getUser());
    }

    /**
     * @runInSeparateProcess
     */
    public function testForgot(): void
    {
        \session_start();
        $this->assertIsString($this->app->getContainer()->auth->forgot('wrong'));
        $this->assertIsString($this->app->getContainer()->auth->forgot('login'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testReset(): void
    {
        \session_start();
        $code = $this->app->getContainer()->auth->forgot('login');
        $this->app->getContainer()['forgot_code'] = $code;
        $this->assertTrue($this->app->getContainer()->auth->reset($code, 'me2'));
    }

    /**
     * @runInSeparateProcess
     */
    public function testResetNull(): void
    {
        \session_start();
        $this->assertFalse($this->app->getContainer()->auth->reset('notexists', 'password'));
    }
}
