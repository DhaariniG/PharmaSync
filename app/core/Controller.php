<?php
/**
 * Controller - base class for every controller.
 *
 * Rules for everyone:
 *   - No SQL here. Ask a model.
 *   - No HTML here. Load a view.
 *   - Call requireRole() in your constructor so you can never forget it.
 */
abstract class Controller
{
    /* ------------------------------------------------------------------
     * Loading models and views
     * ------------------------------------------------------------------ */

    /**
     * Load a model.
     *   $this->model('Medicine')            -> app/models/Medicine.php
     *   $this->model('customer/Cart')       -> app/models/customer/Cart.php
     */
    protected function model(string $name): Model
    {
        $file = APP_PATH . '/models/' . $name . '.php';

        if (!file_exists($file)) {
            throw new RuntimeException("Model not found: $name");
        }

        require_once $file;

        $class = basename($name);
        return new $class();
    }

    /**
     * Render a view.
     *   $this->view('customer/dashboard', ['orders' => $orders])
     * makes $orders available inside app/views/customer/dashboard.php
     */
    protected function view(string $name, array $data = []): void
    {
        $file = APP_PATH . '/views/' . $name . '.php';

        if (!file_exists($file)) {
            throw new RuntimeException("View not found: $name");
        }

        extract($data, EXTR_SKIP);
        require $file;
    }

    /* ------------------------------------------------------------------
     * Access control
     * ------------------------------------------------------------------ */

    /** Any logged-in user. */
    protected function requireLogin(): void
    {
        if (!Session::isLoggedIn()) {
            Session::flash('error', 'Please log in to continue.');
            $this->redirect('/auth/login');
        }
    }

    /**
     * A specific role. Pass the DATABASE value, not the slug.
     *   $this->requireRole('Customer');
     *   $this->requireRole(['Admin', 'Pharmacist']);
     */
    protected function requireRole($roles): void
    {
        $this->requireLogin();

        $allowed = is_array($roles) ? $roles : [$roles];

        if (!in_array(Session::role(), $allowed, true)) {
            http_response_code(403);
            $this->view('errors/forbidden');
            exit;
        }
    }

    /* ------------------------------------------------------------------
     * Requests and redirects
     * ------------------------------------------------------------------ */

    protected function isPost(): bool
    {
        return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
    }

    /** Read and trim one POST field. */
    protected function input(string $key, $default = ''): string
    {
        return trim((string) ($_POST[$key] ?? $default));
    }

    /**
     * Redirect to a path relative to the app root.
     *   $this->redirect('/customer/dashboard');
     */
    protected function redirect(string $path): void
    {
        // Strip any leading slashes to prevent double slashes
        $path = ltrim($path, '/');

        // Clean redirect without index.php?url=
        header('Location: ' . rtrim(BASE_URL, '/') . '/' . $path);
        exit;
    }

    /** Send the logged-in user to their own dashboard. */
    protected function redirectToDashboard(): void
    {
        $slug = Session::roleSlug();
        $this->redirect($slug ? "/$slug/dashboard" : '/auth/login');
    }

    /** Return JSON, for AJAX endpoints. */
    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }

    /* ------------------------------------------------------------------
     * CSRF protection
     * ------------------------------------------------------------------
     * Every POST form must contain:
     *   <?= csrf_field() ?>
     * Every POST handler must start with:
     *   $this->checkCsrf();
     */
    protected function checkCsrf(): void
    {
        $sent   = $_POST['csrf_token'] ?? '';
        $stored = $_SESSION['csrf_token'] ?? '';

        if ($sent === '' || $stored === '' || !hash_equals($stored, $sent)) {
            http_response_code(419);
            Session::flash('error', 'Your session expired. Please try again.');
            $this->redirectToDashboard();
        }
    }

    /* ------------------------------------------------------------------
     * Simple validation
     * ------------------------------------------------------------------
     * Returns an array of error messages, empty if everything passed.
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
                            $errors[$field] = "Enter a valid email address.";
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
                    break;   // one error per field is enough
                }
            }
        }

        return $errors;
    }
}
