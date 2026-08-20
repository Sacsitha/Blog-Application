<?php
require_once '../../config.php';
require_once '../../includes/functions.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(["error" => "Method not allowed"], 405);
}

$data = getRequestBody();

// Validate input
if (!isset($data['username']) || !isset($data['password'])) {
    sendResponse(["error" => "Username and password are required"], 400);
}

$username = trim($data['username']);
$password = trim($data['password']);

// Find user
$user = fetchOne($conn, "SELECT user_id, username, password, full_name, email, bio, avatar_url FROM user WHERE username = ?", [$username]);

if (!$user) {
    sendResponse(["error" => "Invalid credentials"], 401);
}

// Verify password
if (!verifyPassword($password, $user['password'])) {
    sendResponse(["error" => "Invalid credentials"], 401);
}

// Remove password from response
unset($user['password']);

sendResponse([
    "success" => true,
    "message" => "Login successful",
    "user" => $user,
    "token" => generateToken($user['user_id'], $user['username'])
], 200);
?>
