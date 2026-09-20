<?php
/**
 * App - the router.
 *
 * URL shape:   /{role}/{controller}/{method}/{param}/{param}
 *
 *   /customer/dashboard
 *        -> app/controllers/customer/Dashboard.php  ->  index()
 *
 *   /customer/cart/add/17
 *        -> app/controllers/customer/Cart.php       ->  add(17)
 *
 *   /auth/login
 *        -> app/controllers/Auth.php                ->  login()
 *
 *   /
 *        -> app/controllers/Home.php                ->  index()
 *
 * Because the role is a folder, all five of us can have our own
 * Dashboard.php with no filename clashes.
 */
class App
{
    private string $folder     = '';
    private string $controller = 'Home';
    private string $method     = 'index';
    private array  $params     = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        // ---- 1. Role folder? ----------------------------------------
        if (isset($url[0]) && isset(SLUG_ROLES[strtolower($url[0])])) {
            $this->folder = strtolower(array_shift($url)) . '/';
        }

        $dir = APP_PATH . '/controllers/' . $this->folder;

        // ---- 2. Controller ------------------------------------------
        if (isset($url[0])) {
            $candidate = $this->studly($url[0]);
            if ($this->isSafeName($candidate) && file_exists($dir . $candidate . '.php')) {
                $this->controller = $candidate;
                array_shift($url);
            } elseif ($this->folder !== '') {
                // /customer/nonsense -> 404 rather than silently loading Home
                $this->notFound();
            }
        }

        $file = $dir . $this->controller . '.php';

        if (!file_exists($file)) {
            $this->notFound();
        }

        require_once $file;

        if (!class_exists($this->controller)) {
            $this->notFound();
        }

        $instance = new $this->controller();

        // ---- 3. Method ----------------------------------------------
        if (isset($url[0])) {
            $candidate = $this->camel($url[0]);
            if ($this->isCallable($instance, $candidate)) {
                $this->method = $candidate;
                array_shift($url);
            } else {
                $this->notFound();
            }
        }

        if (!$this->isCallable($instance, $this->method)) {
            $this->notFound();
        }

        // ---- 4. Remaining segments are parameters -------------------
        $this->params = array_values($url);

        call_user_func_array([$instance, $this->method], $this->params);
    }

    /* ---------------------------------------------------------------- */

    private function parseUrl(): array
    {
        if (empty($_GET['url'])) {
            return [];
        }

        $url = rtrim($_GET['url'], '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);

        return array_values(array_filter(explode('/', $url), fn($s) => $s !== ''));
    }

    /** Only allow public methods that are not inherited from Controller. */
    private function isCallable(object $instance, string $method): bool
    {
        if (!method_exists($instance, $method)) {
            return false;
        }

        $ref = new ReflectionMethod($instance, $method);

        return $ref->isPublic()
            && !$ref->isStatic()
            && !$ref->isConstructor()
            && $ref->getDeclaringClass()->getName() !== 'Controller';
    }

    /** cart_history -> CartHistory */
    private function studly(string $s): string
    {
        return str_replace(' ', '', ucwords(str_replace(['-', '_'], ' ', strtolower($s))));
    }

    /** view-order -> viewOrder */
    private function camel(string $s): string
    {
        return lcfirst($this->studly($s));
    }

    /** Blocks path traversal attempts like ../../config */
    private function isSafeName(string $name): bool
    {
        return (bool) preg_match('/^[A-Za-z][A-Za-z0-9]*$/', $name);
    }

    private function notFound(): void
    {
        http_response_code(404);
        require APP_PATH . '/views/404.php';
        exit;
    }
}
