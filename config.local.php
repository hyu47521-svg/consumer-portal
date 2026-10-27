<?php
// Secrets live in config.local.php, which is excluded from GitHub by .gitignore.
// Copy config.local.example.php to config.local.php and fill in your values.
$localConfig = __DIR__ . '/config.local.php';
if (!file_exists($localConfig)) {
    http_response_code(500);
    exit('Missing config.local.php. See config.local.example.php.');
}
require_once $localConfig;

// Public, non-secret settings
defined('SITE_NAME')     || define('SITE_NAME', 'Consumer Rights Portal');
defined('SITE_URL')      || define('SITE_URL', 'https://yourdomain.com');
defined('CONTACT_EMAIL') || define('CONTACT_EMAIL', 'support@yourdomain.com');

// Show errors only while developing. Set to 0 on the live site.
ini_set('display_errors', '0');
error_reporting(E_ALL);

// Secure session cookies
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'secure'   => !empty($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax',
]);
session_start();

// Database connection (PDO with prepared statements)
try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    exit('The site is temporarily unavailable. Please try again later.');
}

// Escape output to prevent XSS
function e(?string $value): string {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// CSRF protection: call csrf_field() in forms, verify_csrf() on POST
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}
function csrf_field(): string {
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}
function verify_csrf(): void {
    if (!isset($_POST['csrf']) || !hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'])) {
        http_response_code(403);
        exit('Invalid or expired form. Please go back and try again.');
    }
}

// Login helpers
function current_user(): ?array {
    return $_SESSION['user'] ?? null;
}
function require_login(): void {
    if (!current_user()) {
        header('Location: /login.php');
        exit;
    }
}
function require_role(string ...$roles): void {
    $user = current_user();
    if (!$user || !in_array($user['role'], $roles, true)) {
        http_response_code(403);
        exit('Access denied.');
    }
}

// Generates a unique complaint reference like CMP-2026-000123
function generate_reference(PDO $pdo): string {
    $year = date('Y');
    $next = (int)$pdo->query('SELECT COALESCE(MAX(id), 0) + 1 FROM complaints')->fetchColumn();
    return sprintf('CMP-%s-%06d', $year, $next);
}
