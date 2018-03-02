<?php

declare(strict_types=1);

return [
    'entity' => 'user',
    'storage' => \Wtf\Auth\Storage\JWT::class,
    'repository' => \Wtf\Auth\Repository\User::class,
    'password' => [
        'algo' => PASSWORD_DEFAULT,
        'options' => [],
    ],
];
