<?php

declare(strict_types=1);

namespace Wtf\Auth;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class Provider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $repository = $container['config']('auth.repository');
        $storage = $container['config']('auth.storage');
        $container['auth_repository'] = function ($c) use ($repository) {
            return new $repository($c);
        };
        $container['auth_storage'] = function ($c) use ($storage) {
            return new $storage($c);
        };
        $container['auth'] = function ($c) {
            return new \Wtf\Auth($c);
        };
    }
}
