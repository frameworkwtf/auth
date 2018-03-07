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
        //@codeCoverageIgnoreStart
        if (!class_exists(\Dflydev\FigCookies\Cookie::class)) {
            throw new \Exception('wtf/auth cookie storage requires dflydev/fig-cookies package installed');
        }
        //@codeCoverageIgnoreEnd

        return \Dflydev\FigCookies\Cookie::create('user_id', $user->getId());
    }

    /**
     * {@inheritdoc}
     */
    public function getUser(): ?Root
    {
        //@codeCoverageIgnoreStart
        if (!class_exists(\Dflydev\FigCookies\Cookie::class)) {
            throw new \Exception('wtf/auth cookie storage requires dflydev/fig-cookies package installed');
        }
        //@codeCoverageIgnoreEnd

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
        //@codeCoverageIgnoreStart
        if (!class_exists(\Dflydev\FigCookies\Cookie::class)) {
            throw new \Exception('wtf/auth cookie storage requires dflydev/fig-cookies package installed');
        }
        //@codeCoverageIgnoreEnd

        return (bool) $this->getUser();
    }

    /**
     * {@inheritdoc}
     */
    public function logout(): void
    {
        $request = $this->request;
        unset($this->container['request']);
        $this->container['request'] = \Dflydev\FigCookies\FigRequestCookies::modify($request, 'user_id', function (\Dflydev\FigCookies\Cookie $cookie) {
            return $cookie->withValue(null);
        });
    }
}
