<?php
/**
 * Controller - base class for every controller in the project.
 *
 * Rules for everyone:
 *   - No SQL here. Ask a model.
 *   - No HTML here. Render a view.
 *   - Start every page method with $this->requireRole('YourRole').
 *   - Start every POST method with $this->verifyCsrf().
 */
class Controller
{
    /**
     * Default view folder for this controller, e.g. 'customer'.
     * Set it once at the top of your controller and every render() call
     * in that class looks inside app/views/<that folder>/.
     */
    protected string $viewBase = '';

    /* ==================================================================
     * Views
     * ================================================================== */

    /**
     * Render a view wrapped in your role's header and footer.
     *
     *   $this->render('cart.index', ['items' => $items]);
     *   -> app/views/customer/cart/index.php
     *      between app/views/customer/partials/header.php and footer.php
     *
     * Dots are folder separators, so 'order.show' means order/show.php.
     */
    protected function render(string $view, array $data = [], ?string $base = null): void
    {
        $base = $base ?? $this->viewBase;
        $dir  = APP_PATH . '/views/' . $base;
        $file = $dir . '/' . str_replace('.', '/', $view) . '.php';

        if (!is_file($file)) {
            $this->viewMissing($base, $view);
            return;
        }

        extract($data, EXTR_SKIP);

        $header = $dir . '/partials/header.php';
        $footer = $dir . '/partials/footer.php';

        if (is_file($header)) {
            require $header;
        }

        require $file;

        if (is_file($footer)) {
            require $footer;
        }
    }

    /**
     * Render a view on its own - no header, no footer.
     * Use it for printable pages, modal bodies and AJAX fragments.
     */
    protected function renderBare(string $view, array $data = [], ?string $base = null): void
    {
        $base = $base ?? $this->viewBase;
        $file = APP_PATH . '/views/' . $base . '/' . str_replace('.', '/', $view) . '.php';

        if (!is_file($file)) {
            $this->viewMissing($base, $view);
            return;
        }

        extract($data, EXTR_SKIP);
        require $file;
    }

    /** Render a view that is shared by everyone, e.g. 'errors/404'. */
    protected function renderShared(string $view, array $data = []): void
    {
        $file = APP_PATH . '/views/' . str_replace('.', '/', $view) . '.php';

        if (!is_file($file)) {
            $this->viewMissing('', $view);
            return;
        }

        extract($data, EXTR_SKIP);
        require $file;
    }

    private function viewMissing(string $base, string $view): void
    {
        http_response_code(500);

        if (APP_ENV === 'dev') {
            echo 'View not found: ' . e(trim($base . '/' . $view, '/'));
        } else {
            echo 'Something went wrong.';
        }
    }

    /* ==================================================================
     * Models
     * ================================================================== */

    /**
     * Load a model by class name. Models are autoloaded, so this is only a
     * convenience: $this->model('Cart') is the same as new Cart().
     */
    protected function model(string $name): Model
    {
        if (!class_exists($name)) {
            throw new RuntimeException("Model not found: $name");
        }
        return new $name();
    }

    /* ==================================================================
     * Access control
     * ================================================================== */

    /**
     * Any signed-in user. Where they were heading is remembered, so the
     * shared login can send them back after they sign in.
     */
    protected function requireAuth(string $reason = 'Please sign in to continue.'): void
    {
        if (Session::isLoggedIn()) {
            return;
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET') {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? url('/');
        }

        Session::flash('error', $reason);
        header('Location: ' . url('/' . AUTH_SLUG . '/login'));
        exit;
    }

    /**
     * A specific role. Pass the DATABASE spelling, not the URL segment.
     *
     *   $this->requireRole('Customer');
     *   $this->requireRole(['Admin', 'Pharmacist']);
     */
    protected function requireRole($roles): void
    {
        $this->requireAuth();

        $allowed = is_array($roles) ? $roles : [$roles];

        if (!in_array(Session::role(), $allowed, true)) {
            http_response_code(403);
            $this->renderShared('errors/forbidden');
            exit;
        }
    }

    /** The signed-in user array, or null. */
    protected function currentUser(): ?array
    {
        return Session::user();
    }

    /* ==================================================================
     * Request
     * ================================================================== */

    protected function isPost(): bool
    {
        return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
    }

    /** One POST or GET field. */
    protected function input(string $key, $default = null)
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    /** One POST or GET field, trimmed to a string. */
    protected function text(string $key, string $default = ''): string
    {
        return trim((string) ($_POST[$key] ?? $_GET[$key] ?? $default));
    }

    /* ==================================================================
     * Responses
     * ================================================================== */

    /**
     * Redirect to an app path. Always include the role segment.
     *
     *   $this->redirect('/customer/cart');
     */
    protected function redirect(string $path): void
    {
        header('Location: ' . url($path));
        exit;
    }

    /** Send the signed-in user to their own dashboard. */
    protected function redirectToDashboard(): void
    {
        $slug = Session::roleSlug();
        $this->redirect($slug ? "/$slug/dashboard" : '/' . AUTH_SLUG . '/login');
    }

    /** JSON response, for AJAX endpoints. */
    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }

    /** 404 from inside a controller, e.g. when an id does not exist. */
    protected function notFound(): void
    {
        http_response_code(404);
        $this->renderShared('errors/404');
        exit;
    }

    /* ==================================================================
     * Flash messages
     * ==================================================================
     * Two ways to call it, both kept because both are in use:
     *   $this->flash('success', 'Saved.');   // store
     *   $msg = $this->flash('success');      // read once and clear
     */
    protected function flash(string $key, ?string $message = null)
    {
        if ($message !== null) {
            Session::flash($key, $message);
            return null;
        }

        $value = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $value;
    }

    /* ==================================================================
     * CSRF
     * ==================================================================
     * Every POST form needs <?= csrf_field() ?> and every POST handler
     * needs $this->verifyCsrf() on its first line. Field name: _csrf.
     */
    protected function csrfToken(): string
    {
        return csrf_token();
    }

    protected function verifyCsrf(): void
    {
        $submitted = (string) ($_POST['_csrf'] ?? '');
        $expected  = $_SESSION['csrf_token'] ?? '';

        if ($expected === '' || !hash_equals($expected, $submitted)) {
            http_response_code(419);
            Session::flash('error', 'Your session expired or the form was invalid. Please try again.');
            header('Location: ' . $this->safeReferer('/'));
            exit;
        }
    }

    /** The referring page, but only if it is on this site. */
    protected function safeReferer(string $fallback = '/'): string
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '';

        if ($referer !== '') {
            $refHost  = parse_url($referer, PHP_URL_HOST);
            $selfHost = $_SERVER['HTTP_HOST'] ?? '';

            if ($refHost === null || $refHost === $selfHost) {
                $path  = parse_url($referer, PHP_URL_PATH) ?? '';
                $query = parse_url($referer, PHP_URL_QUERY);

                if ($path !== '' && $path[0] === '/') {
                    return $path . ($query ? '?' . $query : '');
                }
            }
        }

        return url($fallback);
    }

    /* ==================================================================
     * Validation
     * ==================================================================
     * Returns an array of messages keyed by field, empty if all passed.
     *
     *   $errors = $this->validate($_POST, [
     *       'email' => 'required|email',
     *       'phone' => 'required|min:10',
     *       'qty'   => 'required|int',
     *   ]);
     */
    protected function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $ruleString) {
            $value = trim((string) ($data[$field] ?? ''));
            $label = ucfirst(str_replace('_', ' ', $field));

            foreach (explode('|', $ruleString) as $rule) {
                [$name, $param] = array_pad(explode(':', $rule, 2), 2, null);

                switch ($name) {
                    case 'required':
                        if ($value === '') {
                            $errors[$field] = "$label is required.";
                        }
                        break;

                    case 'email':
                        if ($value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $errors[$field] = 'Enter a valid email address.';
                        }
                        break;

                    case 'int':
                        if ($value !== '' && filter_var($value, FILTER_VALIDATE_INT) === false) {
                            $errors[$field] = "$label must be a whole number.";
                        }
                        break;

                    case 'numeric':
                        if ($value !== '' && !is_numeric($value)) {
                            $errors[$field] = "$label must be a number.";
                        }
                        break;

                    case 'min':
                        if ($value !== '' && mb_strlen($value) < (int) $param) {
                            $errors[$field] = "$label must be at least $param characters.";
                        }
                        break;

                    case 'max':
                        if ($value !== '' && mb_strlen($value) > (int) $param) {
                            $errors[$field] = "$label must be $param characters or fewer.";
                        }
                        break;

                    case 'match':
                        if ($value !== (string) ($data[$param] ?? '')) {
                            $errors[$field] = "$label does not match.";
                        }
                        break;
                }

                if (isset($errors[$field])) {
                    break;   // one message per field is enough
                }
            }
        }

        return $errors;
    }
}
