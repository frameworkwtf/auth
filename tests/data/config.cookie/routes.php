<?php

declare(strict_types=1);

return [
    '/test' => [
        'default' => [
            'pattern' => '/default',
            'rbac' => [
                'anonymous' => ['GET', 'POST'],
            ],
        ],
        'user' => [
            'pattern' => '/user',
            'rbac' => [
                'user' => ['GET', 'POST'],
            ],
        ],
    ],
];
