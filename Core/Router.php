<?php

declare(strict_types=1);

namespace Core;

/**
 * Router
 */
class Router
{
    /**
     * Associative array of routes (the routing table)
     *
     * @var array<string, array<string, mixed>>
     */
    protected array $routes = [];

    /**
     * Parameters from the matched route
     *
     * @var array<string, mixed>
     */
    protected array $params = [];

    /**
     * Add a route to the routing table
     *
     * @param array<string, mixed> $params Parameters (controller, action, etc.)
     */
    public function add(string $route, array $params = []): void
    {
        // Convert the route to a regular expression: escape forward slashes
        $route = preg_replace('/\//', '\\/', $route) ?? $route;

        // Convert variables e.g. {controller}
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route) ?? $route;

        // Convert variables with custom regular expressions e.g. {id:\d+}
        // Only allow safe characters in custom patterns to reduce ReDoS / injection risk
        $route = preg_replace_callback(
            '/\{([a-z]+):([^\}]+)\}/',
            static function (array $matches): string {
                $name = $matches[1];
                $pattern = $matches[2];

                if (preg_match('/^[a-zA-Z0-9\_\|\^\-\$\.\+\*\(\)\[\]\\\\]+$/', $pattern) !== 1) {
                    throw new \InvalidArgumentException("Unsafe route pattern for parameter {{$name}}");
                }

                return '(?P<' . $name . '>' . $pattern . ')';
            },
            $route
        ) ?? $route;

        // Add start and end delimiters, and case insensitive flag
        $route = '/^' . $route . '$/i';

        $this->routes[$route] = $params;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Match the route to the routes in the routing table
     */
    public function match(string $url): bool
    {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches) === 1) {
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }

                $this->params = $params;
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<string, mixed>
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * Dispatch the route, creating the controller object and running the action
     *
     * @throws \Exception
     */
    public function dispatch(string $url): void
    {
        $url = $this->removeQueryStringVariables($url);

        if (!$this->match($url)) {
            throw new \Exception('No route matched.', 404);
        }

        $controller = $this->params['controller'] ?? null;
        $action = $this->params['action'] ?? null;

        if (!is_string($controller) || !is_string($action)) {
            throw new \Exception('Route must define controller and action.', 404);
        }

        if (preg_match('/^[a-z]+(?:-[a-z]+)*$/i', $controller) !== 1) {
            throw new \Exception('Invalid controller name.', 404);
        }

        if (preg_match('/^[a-z]+(?:-[a-z]+)*$/i', $action) !== 1) {
            throw new \Exception('Invalid action name.', 404);
        }

        $controller = $this->convertToStudlyCaps($controller);
        $controller = $this->getNamespace() . $controller;

        if (!class_exists($controller)) {
            throw new \Exception("Controller class $controller not found", 404);
        }

        $controllerObject = new $controller($this->params);

        if (!$controllerObject instanceof Controller) {
            throw new \Exception("Controller class $controller must extend Core\\Controller");
        }

        $action = $this->convertToCamelCase($action);

        // Prevent calling *Action methods directly; only allow the short name
        // so filters run through Controller::__call
        if (preg_match('/action$/i', $action) === 1) {
            throw new \Exception(
                "Method $action in controller $controller cannot be called directly - remove the Action suffix to call this method"
            );
        }

        $method = $action . 'Action';
        $reflectionClass = new \ReflectionClass($controllerObject);

        // Force the __call filter path: the short name must not exist as a real method
        if ($reflectionClass->hasMethod($action)) {
            throw new \Exception(
                "Define actions as {$method}(); method {$action}() must not exist on $controller"
            );
        }

        if (!$reflectionClass->hasMethod($method)) {
            throw new \Exception("Method $method not found in controller $controller", 404);
        }

        $reflection = $reflectionClass->getMethod($method);

        if ($reflection->isPrivate() || $reflection->isStatic()) {
            throw new \Exception("Method $method is not accessible in controller $controller", 404);
        }

        // Invoke via short name so Controller::__call applies before/after filters
        $controllerObject->$action();
    }

    protected function convertToStudlyCaps(string $string): string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }

    protected function convertToCamelCase(string $string): string
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }

    /**
     * Remove the query string variables from the URL (if any).
     */
    protected function removeQueryStringVariables(string $url): string
    {
        if ($url === '') {
            return $url;
        }

        $parts = explode('&', $url, 2);

        if (!str_contains($parts[0], '=')) {
            return $parts[0];
        }

        return '';
    }

    /**
     * Get the namespace for the controller class.
     */
    protected function getNamespace(): string
    {
        $namespace = 'App\\Controllers\\';

        if (array_key_exists('namespace', $this->params)) {
            $ns = $this->params['namespace'];

            if (!is_string($ns) || preg_match('/^[A-Za-z][A-Za-z0-9]*(?:\\\\[A-Za-z][A-Za-z0-9]*)*$/', $ns) !== 1) {
                throw new \Exception('Invalid controller namespace.', 404);
            }

            $namespace .= $ns . '\\';
        }

        return $namespace;
    }
}
