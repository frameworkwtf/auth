<?php

declare(strict_types=1);

namespace Wtf\Auth\Storage;

use Wtf\Root;

class JWT extends Root implements StorageInterface
{
    /**
     * {@inheritdoc}
     */
    public function setUser(Root $user)
    {
        if (!class_exists(\Firebase\JWT\JWT::class)) {
            throw new \Exception('wtf/auth jwt storage requires wtf/rest or firebase/php-jwt packages installed');
        }
        $data = $user->getData();
        if ($data['password'] ?? null) {
            unset($data['password']);
        }

        return \Firebase\JWT\JWT::encode([
            'jti' => $user->getId().time().random_int(PHP_INT_MIN, PHP_INT_MAX),
            'iat' => $this->config('jwt.iat', time()),
            'nbf' => $this->config('jwt.nbf', time()),
            'iss' => $this->config('jwt.iss', getenv('APP_HOST')),
            'aud' => $this->config('jwt.aud', getenv('APP_HOST')),
            'exp' => $this->config('jwt.exp', time() + 604800),
            'data' => $data,
        ], getenv('APP_SECRET'), $this->config('jwt.algorithm'.['HS256'])[0]);
    }

    /**
     * {@inheritdoc}
     */
    public function getUser($storage = null): ?Root
    {
        if (!class_exists(\Firebase\JWT\JWT::class)) {
            throw new \Exception('wtf/auth jwt storage requires wtf/rest or firebase/php-jwt packages installed');
        }

        // wtf/rest implementation
        if ($this->container->has('user')) {
            return $this->user;
        }

        // tuupola/slim-jwt-auth implementation
        if ($token = $this->request->getAttribute($this->config('jwt.attribute', 'token'))) {
            return is_object($token) && property_exists($token, 'data') ? $token->data : $token;
        }

        // $storage arg implementation
        if ($storage) {
            $token = \Firebase\JWT\JWT::decode($storage, getenv('APP_SECRET'), $this->config('jwt.algorithm', ['HS256', 'HS512', 'HS384']));

            return is_object($token) && property_exists($token, 'data') ? $token->data : $token;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function isLoggedIn($storage = null): bool
    {
        return (bool) $this->getUser($storage);
    }
}
