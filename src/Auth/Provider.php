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
        $container['user'] = function ($c) {
            return $c['auth']->getUser();
        };

        //@codeCoverageIgnoreStart
        if (\class_exists('\Symfony\Component\Ldap\Ldap')) {
            $container['ldap_client'] = function ($c) {
                $ldap = \Symfony\Component\Ldap\Ldap::create('ext_ldap', $c['config']('auth.ldap.server'));
                $ldap->bind($c['config']('auth.ldap.admin.dn'), $c['config']('auth.ldap.admin.password'));

                return $ldap;
            };
        }
        // if wtf/orm not installed, we use \Wtf\Root class directly
        if (!\class_exists('\Wtf\ORM\Entity')) {
            $container['entity'] = $container->protect(function (string $name) use ($container) {
                return new \Wtf\Root($container);
            });
        }
        //@codeCoverageIgnoreEnd
    }
}
