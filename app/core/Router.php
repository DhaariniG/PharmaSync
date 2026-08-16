<?php

class Router
{
    protected array $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function dispatch(string $method, string $uri): void
    {
        // Strip query string and trailing slash
        $uri = parse_url($uri, PHP_URL_PATH);
        $uri = rtrim($uri, '/');
        if ($uri === '') {
            $uri = '/';
        }

        // Strip the app's base path (e.g. /pharmasync-customer/public) so
        // routes can be defined as clean, absolute paths.
        $base = rtrim(parse_url(BASE_URL, PHP_URL_PATH) ?? '', '/');
        if ($base !== '' && strpos($uri, $base) === 0) {
            $uri = substr($uri, strlen($base));
            if ($uri === '') {
                $uri = '/';
            }
        }

        foreach ($this->routes as $pattern => $action) {
            [$routeMethod, $routeUri] = preg_split('/\s+/', trim($pattern), 2);

            if (strtoupper($routeMethod) !== strtoupper($method)) {
                continue;
            }

            $params = $this->match($routeUri, $uri);
            if ($params !== null) {
                $this->call($action, $params);
                return;
            }
        }

        $this->notFound();
    }

    // Match a route pattern to the URL; returns its params or null.
    protected function match(string $routeUri, string $uri): ?array
    {
        $routeUri = rtrim($routeUri, '/');
        if ($routeUri === '') {
            $routeUri = '/';
        }

        $paramNames = [];
        $regex = preg_replace_callback('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', function ($m) use (&$paramNames) {
            $paramNames[] = $m[1];
            return '([^/]+)';
        }, $routeUri);

        $regex = '#^' . $regex . '$#';

        if (preg_match($regex, $uri, $matches)) {
            array_shift($matches);
            return array_combine($paramNames, $matches);
        }

        return null;
    }

    protected function call(array $action, array $params): void
    {
        [$controllerName, $methodName] = $action;
        $controllerClass = $controllerName;

        if (!class_exists($controllerClass)) {
            $this->notFound();
            return;
        }

        $controller = new $controllerClass();

        if (!method_exists($controller, $methodName)) {
            $this->notFound();
            return;
        }

        call_user_func_array([$controller, $methodName], $params);
    }

    protected function notFound(): void
    {
        http_response_code(404);
        $view = __DIR__ . '/../views/customer/errors/404.php';
        if (file_exists($view)) {
            require $view;
        } else {
            echo '404 - Page not found';
        }
    }
}
