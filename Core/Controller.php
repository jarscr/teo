<?php

declare(strict_types=1);

namespace Core;

/**
 * Base controller
 */
abstract class Controller
{
    /**
     * Parameters from the matched route
     *
     * @var array<string, mixed>
     */
    protected array $route_params = [];

    /**
     * @param array<string, mixed> $route_params Parameters from the route
     */
    public function __construct(array $route_params)
    {
        $this->route_params = $route_params;
    }

    /**
     * Magic method called when a non-existent or inaccessible method is
     * called on an object of this class. Used to execute before and after
     * filter methods on action methods. Action methods need to be named
     * with an "Action" suffix, e.g. indexAction, showAction etc.
     *
     * @param array<int, mixed> $args
     *
     * @throws \Exception
     */
    public function __call(string $name, array $args): mixed
    {
        $method = $name . 'Action';

        if (!method_exists($this, $method)) {
            throw new \Exception(
                "Method $method not found in controller " . static::class,
                404
            );
        }

        if ($this->before() === false) {
            return null;
        }

        $result = $this->$method(...$args);
        $this->after();

        return $result;
    }

    /**
     * Before filter - called before an action method.
     * Return false to stop the action from executing.
     */
    protected function before(): mixed
    {
        return null;
    }

    /**
     * After filter - called after an action method.
     */
    protected function after(): void
    {
    }
}
