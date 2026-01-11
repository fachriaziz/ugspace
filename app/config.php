<?php
// Load .env file
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
  $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  foreach ($lines as $line) {
    if (strpos(trim($line), '#') === 0) continue;
    if (strpos($line, '=') === false) continue;
    list($key, $value) = explode('=', $line, 2);
    $_ENV[trim($key)] = trim($value);
  }
}

// Helper function
function env($key, $default = null)
{
  return $_ENV[$key] ?? $default;
}

// Define constants
define('BASEURL', env('APP_URL'));
define('DB_HOST', env('DB_HOST'));
define('DB_USER', env('DB_USER'));
define('DB_PASS', env('DB_PASS'));
define('DB_NAME', env('DB_NAME'));
define('PER_PAGE', (int) env('PER_PAGE'));

// Set timezone
date_default_timezone_set(env('APP_TIMEZONE'));
