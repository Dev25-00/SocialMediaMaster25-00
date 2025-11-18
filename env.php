<?php
/**
 * Simple .env loader for SMM Mastery
 * - Load variables from .env into $_ENV and getenv()
 * - Use PROJECT_ROOT/.env file
 */

if (!defined('PROJECT_ROOT')) define('PROJECT_ROOT', __DIR__);

$envPath = PROJECT_ROOT . DIRECTORY_SEPARATOR . '.env';
if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (!strpos($line, '=')) continue;
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        // Remove surrounding quotes
        if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') || (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
            $value = substr($value, 1, -1);
        }
        $_ENV[$name] = $value;
        putenv("{$name}={$value}");
    }
}

// Helper to fetch env value with fallback
function env($key, $default = null) {
    if (array_key_exists($key, $_ENV)) return $_ENV[$key];
    $v = getenv($key);
    if ($v !== false) return $v;
    return $default;
}

?>