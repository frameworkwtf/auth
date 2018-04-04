<?php

declare(strict_types=1);

namespace Wtf\Auth\Tests\Middleware;

use PHPUnit\Framework\TestCase;

class RBACTest extends TestCase
{
    protected $request;
    protected $response;
    protected $callable;
    protected $rbac;

    protected function setUp(): void
    {
        $dir = __DIR__.'/../../data/config.cookie';
        $app = new \Wtf\App(['config_dir' => $dir]);
        $this->routeGroup = new \Slim\RouteGroup('/test', $this->callable);
        $this->request = $app->getContainer()->request->withMethod('GET');
        $this->response = $app->getContainer()->response;
        $this->callable = function ($request, $response) {return $response; };
        $this->rbac = new \Wtf\Auth\Middleware\RBAC($app->getContainer());
    }

    public function testRouteNotFound(): void
    {
        $callable = $this->callable;
        $response = $this->rbac->__invoke($this->request, $this->response, $callable);
        $this->assertInstanceOf('\Psr\Http\Message\ResponseInterface', $response);
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testRoleDefault(): void
    {
        $route = new \Slim\Route(['GET', 'POST'], '/test/default', $this->callable, [$this->routeGroup]);
        $request = $this->request->withAttribute('route', $route->setName('test-default'));
        $callable = $this->callable;
        $response = $this->rbac->__invoke($request, $this->response, $callable);
        $this->assertInstanceOf('\Psr\Http\Message\ResponseInterface', $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testRoleDefaultNotAllowed(): void
    {
        $route = new \Slim\Route(['GET', 'POST'], '/test/user', $this->callable, [$this->routeGroup]);
        $request = $this->request->withAttribute('route', $route->setName('test-user'));
        $callable = $this->callable;
        $response = $this->rbac->__invoke($request, $this->response, $callable);
        $this->assertInstanceOf('\Psr\Http\Message\ResponseInterface', $response);
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testRoleUser(): void
    {
        $route = new \Slim\Route(['GET', 'POST'], '/test/user', $this->callable, [$this->routeGroup]);
        $request = $this->request->withAttribute('route', $route->setName('test-user'))->withAttribute('role', 'user');
        $callable = $this->callable;
        $response = $this->rbac->__invoke($request, $this->response, $callable);
        $this->assertInstanceOf('\Psr\Http\Message\ResponseInterface', $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testRoleUserToAnonymous(): void
    {
        $route = new \Slim\Route(['GET', 'POST'], '/test/default', $this->callable, [$this->routeGroup]);
        $request = $this->request->withAttribute('route', $route->setName('test-default'))->withAttribute('role', 'user');
        $callable = $this->callable;
        $response = $this->rbac->__invoke($request, $this->response, $callable);
        $this->assertInstanceOf('\Psr\Http\Message\ResponseInterface', $response);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
