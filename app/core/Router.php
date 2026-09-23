<?php
/**
 * Router - matches a request to one controller method.
 *
 * Routes are declared in config/routes/<role>.php as a table:
 *
 *     'GET  /cart'          => ['CustomerCartController', 'index'],
 *     'POST /cart/add'      => ['CustomerCartController', 'add'],
 *     'GET  /orders/{id}'   => ['CustomerOrderController', 'show'],
 *
 * Paths inside a role file are written WITHOUT the role segment. The loader
 * in config/routes.php adds it, so the routes above are reachable at
 * /customer/cart, /customer/cart/add and /customer/orders/17.
 *
 * That prefix is what stops five people's /dashboard routes from colliding.
 * {id} captures one segment and is passed to the method as an argument.
 */
class Router
{
    protected array $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    public function dispatch(string $method, string $uri): void
    {
        $uri = $this->normalise($uri);

        // HTML forms can only send GET and POST. A hidden _method field lets
        // a form pretend to be PUT or DELETE if anyone wants that later.
        if ($method === 'POST' && !empty($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
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

        $this->notFound($uri);
    }

    /* ------------------------------------------------------------------ */

    /** Strip the query string, the trailing slash and the install folder. */
    protected function normalise(string $uri): string
    {
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';
        $path = rtrim($path, '/');

        $base = rtrim(parse_url(BASE_URL, PHP_URL_PATH) ?? '', '/');

        if ($base !== '' && strpos($path, $base) === 0) {
            $path = substr($path, strlen($base));
        }

        return $path === '' ? '/' : $path;
    }

    /** Match one route pattern against the request path. */
    protected function match(string $routeUri, string $uri): ?array
    {
        $routeUri = rtrim($routeUri, '/');
        if ($routeUri === '') {
            $routeUri = '/';
        }

        $paramNames = [];

        $regex = preg_replace_callback(
            '#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#',
            function ($m) use (&$paramNames) {
                $paramNames[] = $m[1];
                return '([^/]+)';
            },
            $routeUri
        );

        if (!preg_match('#^' . $regex . '$#', $uri, $matches)) {
            return null;
        }

        array_shift($matches);

        return $paramNames ? array_combine($paramNames, $matches) : [];
    }

    /** Instantiate the controller and call the method. */
    protected function call(array $action, array $params): void
    {
        [$controllerName, $methodName] = $action;

        if (!class_exists($controllerName)) {
            $this->serverError("Controller not found: $controllerName");
            return;
        }

        $controller = new $controllerName();

        if (!method_exists($controller, $methodName)) {
            $this->serverError("Method not found: $controllerName::$methodName()");
            return;
        }

        call_user_func_array([$controller, $methodName], array_values($params));
    }

    protected function notFound(string $uri = ''): void
    {
        http_response_code(404);

        $file = APP_PATH . '/views/errors/404.php';

        if (is_file($file)) {
            $requestedPath = $uri;
            require $file;
        } else {
            echo '404 - Page not found';
        }
    }

    protected function serverError(string $message): void
    {
        http_response_code(500);
        echo APP_ENV === 'dev' ? htmlspecialchars($message) : 'Something went wrong.';
    }
}
