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
        //@codeCoverageIgnoreStart
        if (!class_exists(\Firebase\JWT\JWT::class)) {
            throw new \Exception('wtf/auth jwt storage requires wtf/rest or firebase/php-jwt packages installed');
        }
        //@codeCoverageIgnoreEnd
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
        ], getenv('APP_SECRET'), $this->config('jwt.algorithm', ['HS256'])[0]);
    }

    /**
     * {@inheritdoc}
     */
    public function getUser(): ?Root
    {
        //@codeCoverageIgnoreStart
        if (!class_exists(\Firebase\JWT\JWT::class)) {
            throw new \Exception('wtf/auth jwt storage requires wtf/rest or firebase/php-jwt packages installed');
        }
        //@codeCoverageIgnoreEnd

        // wtf/rest implementation
        if ($this->container->has('user')) {
            return $this->entity($this->config('auth.entity'))->setData($this->user);
        }

        // tuupola/slim-jwt-auth implementation
        if ($token = $this->request->getAttribute($this->config('jwt.attribute', 'token'))) {
            $data = (array) (is_object($token) && property_exists($token, 'data') ? $token->data : ($token['data'] ?? $token));

            return $this->entity($this->config('auth.entity'))->setData($data);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function isLoggedIn(): bool
    {
        return (bool) $this->getUser();
    }

    /**
     * {@inheritdoc}
     */
    public function logout(): void
    {
        //nothing to do here
    }
}
