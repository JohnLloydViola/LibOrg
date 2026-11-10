<?php
declare(strict_types=1);

//Secure Session Configuration
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 0,          // Ends when the browser closes
        'path'     => '/',
        'secure'   => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on', // True on production HTTPS
        'httponly' => true,       // Prevents JavaScript access (XSS defense)
        'samesite' => 'Lax'       // Cross-site request mitigation
    ]);

    ini_set('session.use_strict_mode', '1'); // Prevents session fixation
    session_start();
}

function verify_csrf_token(): void {
    $headers = getallheaders();
    $incomingToken = $headers['X-CSRF-TOKEN'] ?? '';

    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $incomingToken)) {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(["message" => "Invalid or missing CSRF token."]);
        exit;
    }
}