<?php
/**
 * check_links.php - static link/path checker for app/views/pharmacist/.
 *
 * Scans every view file under app/views/pharmacist/ for href="...",
 * action="...", src="..." and include/require targets. Anything that
 * resolves to a real file on disk is checked for existence WITH EXACT
 * CASE (Windows/NTFS is case-insensitive by default, so a wrong-case
 * path can look fine here and still 404 on a case-sensitive Linux host -
 * this script deliberately does not trust file_exists()/is_file() alone
 * for that reason).
 *
 * Skipped on purpose (not files, so "OK"/"MISSING" would be meaningless):
 *   - "#" placeholder links
 *   - external URLs (http:// or https://)
 *   - url('/pharmacist/...') calls - these are ROUTES handled by
 *     Router.php, not paths on disk. Printed as ROUTE (skipped) so the
 *     count is visible, but they are not part of the OK/MISSING tally.
 *
 * Resolved and checked as real files:
 *   - asset(...), role_css(...), icon(...) calls -> evaluated with the
 *     app's own helpers so the real ROLE_ASSET_DIRS mapping is used,
 *     then mapped back from URL to a path under public/.
 *   - literal (non-PHP) href/action/src values ending in a file
 *     extension -> resolved relative to public/.
 *   - include/require/include_once/require_once targets -> resolved
 *     relative to the file that contains them.
 *
 * Usage: php dev/check_links.php
 */

require_once __DIR__ . '/../config/config.php';

$viewsRoot = APP_PATH . '/views/pharmacist';

if (!is_dir($viewsRoot)) {
    fwrite(STDERR, "Not found: $viewsRoot\n");
    exit(1);
}

/** List every .php file under a directory, recursively. */
function collectPhpFiles(string $dir): array
{
    $files = [];
    $it = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS)
    );
    foreach ($it as $file) {
        if ($file->isFile() && strtolower($file->getExtension()) === 'php') {
            $files[] = $file->getPathname();
        }
    }
    sort($files);
    return $files;
}

/**
 * Case-sensitive existence check, regardless of the host filesystem's own
 * case sensitivity. Walks the path one segment at a time and compares
 * against the real directory listing at each step.
 */
function existsExactCase(string $absolutePath): bool
{
    $absolutePath = str_replace('\\', '/', $absolutePath);
    $parts = explode('/', ltrim($absolutePath, '/'));

    // Rebuild the root: on Windows this is "C:", on *nix it's "".
    if (preg_match('#^[A-Za-z]:$#', $parts[0] ?? '')) {
        $current = array_shift($parts) . '/';
    } else {
        $current = '/';
    }

    foreach ($parts as $part) {
        if ($part === '') {
            continue;
        }
        if (!is_dir($current) && !is_file($current)) {
            return false;
        }
        $listing = @scandir($current);
        if ($listing === false || !in_array($part, $listing, true)) {
            return false;
        }
        $current = rtrim($current, '/') . '/' . $part;
    }

    return true;
}

/** Turn a BASE_URL-prefixed URL (from asset()/role_css()/icon()) into a disk path under public/. */
function urlToPublicPath(string $urlString): ?string
{
    $baseParts = parse_url(BASE_URL);
    $basePath  = rtrim($baseParts['path'] ?? '', '/'); // e.g. /PharmaSync/public

    $urlParts = parse_url($urlString);
    $path     = $urlParts['path'] ?? '';

    if ($basePath !== '' && strpos($path, $basePath) === 0) {
        $path = substr($path, strlen($basePath));
    }

    return PUBLIC_PATH . '/' . ltrim($path, '/');
}

/** Try to safely evaluate a small whitelist of helper calls with literal-string args. */
function tryEvalHelperCall(string $expr): ?string
{
    $expr = trim($expr);

    // Only allow calls to these exact functions, with arguments made only of
    // string literals, commas, whitespace, and simple '.' concatenation of
    // literals - never arbitrary PHP (no variables, no other calls).
    if (!preg_match('/^(asset|role_css|icon)\s*\((.*)\)$/s', $expr, $m)) {
        return null;
    }

    $fn   = $m[1];
    $args = $m[2];

    // Reject anything that isn't purely string literals / commas / dots / whitespace.
    $stripped = preg_replace("/'(?:[^'\\\\]|\\\\.)*'|\"(?:[^\"\\\\]|\\\\.)*\"/", '', $args);
    if (trim($stripped) !== '' && trim($stripped, " \t\n\r,.") !== '') {
        return null;
    }

    try {
        $result = eval("return $fn($args);");
    } catch (Throwable $e) {
        return null;
    }

    return is_string($result) ? $result : null;
}

$phpFiles = collectPhpFiles($viewsRoot);

$ok = 0;
$missing = 0;
$routesSkipped = 0;
$externalSkipped = 0;
$results = [];

// href="...", action="...", src="..." (single or double quoted)
$attrPattern = '/\b(?:href|action|src)\s*=\s*(["\'])(.*?)\1/s';

// include/require targets - captures the quoted string argument if present.
$includePattern = '/\b(?:include|require)(?:_once)?\s*(?:\()?\s*(["\'])(.*?)\1/';

foreach ($phpFiles as $file) {
    $relFile = str_replace(ROOT_PATH . '/', '', str_replace('\\', '/', $file));
    $rawContents = file_get_contents($file);

    // A short-echo PHP tag can itself contain quote characters that have
    // nothing to do with the surrounding HTML attribute's own quoting (see
    // sale/create.php's form action, which nests a double-quoted string
    // inside a double-quoted attribute). A naive quote-matching regex over
    // the raw text mis-splits that. So PHP tags are pulled out
    // first and replaced with an opaque placeholder before the attribute
    // regex ever runs; the real tag content is looked up afterwards.
    $phpTags = [];
    $contents = preg_replace_callback(
        '/<\?php\b.*?\?>|<\?=.*?\?>/s',
        function ($m) use (&$phpTags) {
            $key = '@@PHPTAG' . count($phpTags) . '@@';
            $phpTags[$key] = $m[0];
            return $key;
        },
        $rawContents
    );

    // --- href / action / src -------------------------------------------------
    if (preg_match_all($attrPattern, $contents, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            $raw = trim($match[2]);

            if ($raw === '#' || $raw === '') {
                continue;
            }

            // Pull the PHP expression out of a short-echo or full PHP tag, if any.
            $phpExpr = null;
            if (isset($phpTags[$raw])
                && preg_match('/<\?(?:php)?=?\s*(.*?)\s*;?\s*\?>/s', $phpTags[$raw], $pm)) {
                $phpExpr = trim($pm[1]);
            }

            if ($phpExpr !== null) {
                if (preg_match('/^url\s*\(/', $phpExpr)) {
                    $routesSkipped++;
                    $results[] = "ROUTE (skipped) $relFile -> $phpExpr";
                    continue;
                }

                $resolvedUrl = tryEvalHelperCall($phpExpr);
                if ($resolvedUrl === null) {
                    // Not a recognised, safely-evaluable helper call - skip rather
                    // than guess (e.g. dynamic values built from $variables).
                    continue;
                }

                // Note: asset()/role_css()/icon() are always built from
                // BASE_URL (see config/helpers.php), so the result always
                // starts with http(s):// too - that prefix does NOT mean
                // "external" here the way it does for a literal attribute.
                $diskPath = urlToPublicPath($resolvedUrl);
            } else {
                // Literal, non-PHP attribute value.
                if (preg_match('#^https?://#i', $raw)) {
                    $externalSkipped++;
                    continue;
                }
                if (!preg_match('/\.[a-zA-Z0-9]{2,5}$/', $raw)) {
                    // No file extension - not something meant to be a file
                    // (e.g. an anchor like "#catalog" already handled above,
                    // or a bare literal route segment). Skip rather than guess.
                    continue;
                }
                $diskPath = urlToPublicPath($raw);
            }

            $exists = existsExactCase($diskPath);
            $relDisk = str_replace('\\', '/', str_replace(ROOT_PATH . '/', '', $diskPath));

            if ($exists) {
                $ok++;
                $results[] = "OK $relDisk   (from $relFile)";
            } else {
                $missing++;
                $results[] = "MISSING $relDisk   (from $relFile)";
            }
        }
    }

    // --- include / require ----------------------------------------------------
    // Run against the raw text, not the placeholder-substituted copy: these
    // are plain PHP statements, not HTML attribute values, so there is no
    // quote-nesting ambiguity to guard against here.
    if (preg_match_all($includePattern, $rawContents, $matches, PREG_SET_ORDER)) {
        foreach ($matches as $match) {
            $raw = trim($match[2]);

            if ($raw === '' || strpos($raw, '$') !== false) {
                // Built from a variable/constant we can't safely resolve statically.
                continue;
            }

            $diskPath = $raw[0] === '/' || preg_match('/^[A-Za-z]:/', $raw)
                ? $raw
                : dirname($file) . '/' . $raw;

            $diskPath = str_replace('\\', '/', $diskPath);
            $exists = existsExactCase($diskPath);
            $relDisk = str_replace(ROOT_PATH . '/', '', $diskPath);

            if ($exists) {
                $ok++;
                $results[] = "OK $relDisk   (from $relFile)";
            } else {
                $missing++;
                $results[] = "MISSING $relDisk   (from $relFile)";
            }
        }
    }
}

foreach ($results as $line) {
    echo $line . "\n";
}

echo "\n--- summary ---\n";
echo "OK: $ok\n";
echo "MISSING: $missing\n";
echo "Routes skipped (not disk paths): $routesSkipped\n";
echo "External URLs skipped: $externalSkipped\n";
