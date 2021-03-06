<?php

declare(strict_types=1);

namespace Wtf\Auth\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Wtf\Root;

/**
 * Role-Based Access Control.
 */
class RBAC extends Root
{
    /**
     * Run RBAC check.
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next): ResponseInterface
    {
        //404 if route not found
        if (!$request->getAttribute('route')) {
            return $this->notFoundHandler->__invoke($request, $response);
        }

        // Check if allowed for current role (if exists) or for anonymous role
        if ($this->isAllowed($request, $request->getAttribute('role', '')) || $this->isAllowed($request, $this->config('auth.rbac.defaultRole', 'anonymous'))) {
            // Run next middleware if allowed
            return $next($request, $response);
        }

        // Return error if not allowed
        return $this->config('auth.rbac.errorCallback') ? $this->config('auth.rbac.errorCallback')($request, $response) : $response->withJson([
            'error' => [
                'message' => 'You are note allowed to see this page',
                'fields' => [],
            ],
            'count' => 0,
            'data' => [],
        ], 401);
    }

    /**
     * Check if request allowed for current user.
     */
    protected function isAllowed(ServerRequestInterface $request, string $role): bool
    {
        $route = $request->getAttribute('route');
        // Get config string like 'routes./home.second.rbac.anonymous' to get rbac config for route with name "second" in route group "home"
        $config = 'routes.'.$route->getGroups()[0]->getPattern().'.'.\strtr('/'.$route->getName(), [$route->getGroups()[0]->getPattern() => '', '-' => '']).'.rbac.'.$role;
        if (\in_array($request->getMethod(), $this->config($config, []), true)) {
            return true;
        }

        return false;
    }
}
