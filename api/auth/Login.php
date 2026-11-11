<?php
declare(strict_types=1);

header("Content-Type: application/json");

require_once __DIR__ . "/../../config/Database.php";
require_once __DIR__ . "/../../config/session.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["message" => "Method Not Allowed"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(["message" => "Invalid JSON data"]);
    exit;
}

$email = $data["email"] ?? "";
$password = $data["password"] ?? "";

if (!is_string($email) || !is_string($password)) {
    http_response_code(422);

    echo json_encode([
        "errors" => [
            "email" => "Invalid email",
            "password" => "Invalid password"
        ]
    ]);

    exit;
}

$email = trim($email);

$errors = validateLogin($email, $password);

if (!empty($errors)) {
    http_response_code(422);
    echo json_encode([
        "errors" => $errors
    ]);
    exit;
}

$member = loginMember($conn, $email);

if (!$member || !password_verify($password, $member["password_hash"])) {
    http_response_code(401);
    echo json_encode([
        "message" => "Invalid email or password."
    ]);
    exit;
}

//Generate authentication token for laravel api
$apiToken = bin2hex(random_bytes(32));
//Store token on database
$updateSql = "UPDATE members SET api_token = :token WHERE id = :id";
$updateStmt = $conn->prepare($updateSql);
$updateStmt->execute([
    ":token" => $apiToken,
    ":id" => $member["id"]
]);

session_regenerate_id(true);

// Generate CSRF token for the session if it doesn't exist yet
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

$_SESSION["member_id"] = $member["id"];
$_SESSION["role"] = $member["role"];
$_SESSION["email"] = $member["email"];

$redirect = $member["role"] === "admin"
    ? "./DashboardPage.php"
    : "./member/BrowseBooksPage.php";

http_response_code(200);
echo json_encode([
    "message" => "Login successful.",
    "redirect" => $redirect,
    "csrf_token" => $_SESSION["csrf_token"],
    "token" => $apiToken
]);

exit;

function loginMember(
    PDO $conn,
    string $email
): array|false {
    $sql = "SELECT id, email, password_hash, role
            FROM members
            WHERE email = :email
            LIMIT 1";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        ":email" => $email
    ]);

    return $stmt->fetch();
}

function validateLogin(
    string $email,
    string $password
): array {
    $errors = [];

    if ($email === "") {
        $errors["email"] = "Email Is Required";
    } elseif (strlen($email) > 255) {
        $errors["email"] = "Email must not exceed 255 characters";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Invalid Email";
    }

     // Password
    if (trim($password) === "") {
        $errors["password"] = "Password Is Required";
    } elseif (strlen($password) > 64) {
        $errors["password"] = "Password must not exceed 64 characters";
    }

    return $errors;
}