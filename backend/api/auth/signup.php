<?php
require_once '../../config.php';
require_once '../../includes/functions.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(["error" => "Method not allowed"], 405);
}

$data = getRequestBody();

// Validate input
if (!isset($data['username']) || !isset($data['password']) || !isset($data['full_name']) || !isset($data['email'])) {
    sendResponse(["error" => "Missing required fields"], 400);
}

$username = trim($data['username']);
$password = trim($data['password']);
$full_name = trim($data['full_name']);
$email = trim($data['email']);
$interests = isset($data['interests']) && is_array($data['interests']) ? $data['interests'] : [];

// Validate email
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendResponse(["error" => "Invalid email format"], 400);
}

// Check if username already exists
$existingUser = fetchOne($conn, "SELECT user_id FROM user WHERE username = ?", [$username]);
if ($existingUser) {
    sendResponse(["error" => "Username already exists"], 409);
}

// Check if email already exists
$existingEmail = fetchOne($conn, "SELECT user_id FROM user WHERE email = ?", [$email]);
if ($existingEmail) {
    sendResponse(["error" => "Email already registered"], 409);
}

// Hash password
$hashedPassword = hashPassword($password);

// Insert user
$query = "INSERT INTO user (username, password, full_name, email, bio, avatar_url, created_at, updated_at) 
          VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";
$result = insert($conn, $query, [$username, $hashedPassword, $full_name, $email, '', '']);

if (isset($result['error'])) {
    sendResponse($result, 500);
}

// Get user data
$user = fetchOne($conn, "SELECT user_id, username, full_name, email, bio, avatar_url, created_at FROM user WHERE user_id = ?", [(string)$result['id']]);

foreach ($interests as $interest_id) {
    insert($conn, "INSERT IGNORE INTO user_interest (user_id, interest_id) VALUES (?, ?)", [(string)$user['user_id'], (string)(int)$interest_id]);
}

sendResponse([
    "success" => true,
    "message" => "Account created successfully",
    "user" => $user,
    "token" => generateToken($user['user_id'], $user['username'])
], 201);
?>
