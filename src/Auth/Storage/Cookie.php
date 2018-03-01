<?php

declare(strict_types=1);

namespace Wtf\Auth\Storage;

use Wtf\Root;

class Cookie extends Root implements StorageInterface
{
    /**
     * {@inheritdoc}
     */
    public function setUser(Root $user)
    {
        if (!class_exists(\Dflydev\FigCookies\Cookie::class)) {
            throw new \Exception('wtf/auth cookie storage requires dflydev/fig-cookies package installed');
        }

        return \Dflydev\FigCookies\Cookie::create('user_id', $user->getId())
                                                                  ->rememberForever()
                                                                  ->withDomain(getenv('APP_HOST'))
                                                                  ->withHttpOnly(true);
    }

    /**
     * {@inheritdoc}
     */
    public function getUser(): ?Root
    {
        if (!class_exists(\Dflydev\FigCookies\Cookie::class)) {
            throw new \Exception('wtf/auth cookie storage requires dflydev/fig-cookies package installed');
        }

        if ($id = \Dflydev\FigCookies\FigRequestCookies::get($this->request, 'user_id', null)->getValue()) {
            $entity = $this->entity($this->config('auth.entity'));
            if ($entity->has(['id' => $id])) {
                return $entity->load($id);
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function isLoggedIn(): bool
    {
        if (!class_exists(\Dflydev\FigCookies\Cookie::class)) {
            throw new \Exception('wtf/auth cookie storage requires dflydev/fig-cookies package installed');
        }

        return (bool) \Dflydev\FigCookies\FigRequestCookies::get($this->request, 'user_id', null)->getValue();
    }
}
