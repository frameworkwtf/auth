<?php

declare(strict_types=1);

namespace Wtf\Auth;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class Provider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container['rbac_middleware'] = function ($c) {
            return new Middleware\RBAC($c);
        };
        $container['auth_repository'] = function ($c) {
            $class = $c['config']('auth.repository');

            return new $class($c);
        };
        $container['auth_storage'] = function ($c) {
            $class = $c['config']('auth.storage');

            return new $class($c);
        };
        $container['auth'] = function ($c) {
            return new \Wtf\Auth($c);
        };
    }
}
