<?php

declare(strict_types=1);

namespace Wtf\Auth\Tests;

use PHPUnit\Framework\TestCase;

class AuthTest extends TestCase
{
    protected $app;

    protected function setUp(): void
    {
        $dir = __DIR__.'/data/config';
        $this->app = new \Wtf\App(['config_dir' => $dir]);
    }
}
