<?php

declare(strict_types=1);

namespace Tests;

use Core\Router;
use PHPUnit\Framework\TestCase;

final class RouterTest extends TestCase
{
    public function testMatchHomeRoute(): void
    {
        $router = new Router();
        $router->add('', ['controller' => 'Home', 'action' => 'index']);

        self::assertTrue($router->match(''));
        self::assertSame('Home', $router->getParams()['controller']);
        self::assertSame('index', $router->getParams()['action']);
    }

    public function testMatchVariableRoute(): void
    {
        $router = new Router();
        $router->add('{controller}/{action}');

        self::assertTrue($router->match('home/index'));
        self::assertSame('home', $router->getParams()['controller']);
        self::assertSame('index', $router->getParams()['action']);
    }

    public function testRejectUnsafeCustomPattern(): void
    {
        $router = new Router();

        $this->expectException(\InvalidArgumentException::class);
        $router->add('{id:(?R)}');
    }
}
