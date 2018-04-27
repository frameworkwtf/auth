<?php

declare(strict_types=1);

return [
    'entity' => 'user',
    'storage' => \Wtf\Auth\Storage\Session::class,
    'repository' => \Wtf\Auth\Repository\LDAP::class,
    'ldap' => [
        'server' => [
            'host' => 'ldap.server',
            'port' => 389,
            'encryption' => 'none',
            'options' => [
                'protocol_version' => 3,
                'referrals' => true,
            ],
        ],
        'admin' => [
            'dn' => 'cn=admin,dc=framework,dc=wtf',
            'password' => 'supersecret',
        ],
        'baseDN' => 'cn=Users,dc=framework,dc=wtf',
        'fields' => [
            'login' => ['uid', 'mail'],
            'loginInDb' => 'email',
            'map' => [
                'cn' => 'name',
            ],
        ],
    ],
    'rbac' => [
        'defaultRole' => 'anonymous',
        'errorCallback' => null,
    ],
];
