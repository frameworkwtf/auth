<?php

declare(strict_types=1);

namespace Wtf\Auth\Tests\Auth;

use PHPUnit\Framework\TestCase;

class ProviderTest extends TestCase
{
    protected $app;

    protected function setUp(): void
    {
        $dir = __DIR__.'/../data/config.jwt';
        $this->app = new \Wtf\App(['config_dir' => $dir]);
    }

    public function testRegister(): void
    {
        $this->assertInstanceOf('\Wtf\Root', $this->app->getContainer()->rbac_middleware);
        $this->assertInstanceOf('\Wtf\Auth\Repository\RepositoryInterface', $this->app->getContainer()->auth_repository);
        $this->assertInstanceOf('\Wtf\Auth\Storage\StorageInterface', $this->app->getContainer()->auth_storage);
        $this->assertInstanceOf('\Wtf\Auth', $this->app->getContainer()->auth);
    }
}
