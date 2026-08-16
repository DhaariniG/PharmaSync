<?php

class Controller
{
    // Render a view inside the shared header/footer.
    protected function render(string $view, array $data = [], string $base = 'customer'): void
    {
        extract($data);

        $viewFile = __DIR__ . '/../views/' . $base . '/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(500);
            echo "View not found: {$base}/{$view}";
            return;
        }

        require __DIR__ . '/../views/customer/partials/header.php';
        require $viewFile;
        require __DIR__ . '/../views/customer/partials/footer.php';
    }

    // Render a view on its own (no header/footer).
    protected function renderBare(string $view, array $data = [], string $base = 'customer'): void
    {
        extract($data);
        $viewFile = __DIR__ . '/../views/' . $base . '/' . str_replace('.', '/', $view) . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(500);
            echo "View not found: {$base}/{$view}";
            return;
        }

        require $viewFile;
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . BASE_URL . $path);
        exit;
    }

    protected function input(string $key, $default = null)
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    // Every page in this module needs a signed-in customer. The shared
    // PharmaSync login is what puts them in $_SESSION['user']; this is only a
    // guard for the case where the session has expired or was never set.
    // Where they were heading is remembered so the shared login can send them
    // back to it after signing in.
    protected function requireAuth(string $reason = 'Please sign in to continue.'): void
    {
        if (!empty($_SESSION['user'])) {
            return;
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'GET') {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'] ?? BASE_URL . '/';
        }
        $this->flash('error', $reason);

        header('Location: ' . BASE_URL . '/');
        exit;
    }

    protected function currentUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    protected function flash(string $key, ?string $message = null)
    {
        if ($message !== null) {
            $_SESSION['flash'][$key] = $message;
            return null;
        }

        $value = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $value;
    }

    protected function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    // Check the CSRF token on POST requests.
    protected function verifyCsrf(): void
    {
        $submitted = (string) ($_POST['_csrf'] ?? '');
        $expected  = $_SESSION['csrf_token'] ?? '';

        if ($expected === '' || !hash_equals($expected, $submitted)) {
            http_response_code(419);
            $this->flash('error', 'Your session expired or the form was invalid. Please try again.');
            $back = $this->safeReferer('/');
            header('Location: ' . $back);
            exit;
        }
    }

    // Return the referring page only if it's on this site, else a safe default.
    protected function safeReferer(string $fallback = '/'): string
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        if ($referer !== '') {
            $refHost  = parse_url($referer, PHP_URL_HOST);
            $selfHost = $_SERVER['HTTP_HOST'] ?? '';
            if ($refHost === null || $refHost === $selfHost) {
                $path = parse_url($referer, PHP_URL_PATH) ?? '';
                $query = parse_url($referer, PHP_URL_QUERY);
                if ($path !== '' && $path[0] === '/') {
                    return $path . ($query ? '?' . $query : '');
                }
            }
        }
        return BASE_URL . $fallback;
    }
}
