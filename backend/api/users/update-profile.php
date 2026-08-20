<?php
require_once '../../config.php';
require_once '../../includes/functions.php';

// Only allow PUT requests
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    sendResponse(["error" => "Method not allowed"], 405);
}

$data = getRequestBody();

// Validate input
if (!isset($data['user_id'])) {
    sendResponse(["error" => "user_id is required"], 400);
}

$user_id = trim($data['user_id']);

// Verify user exists
$user = fetchOne($conn, "SELECT user_id FROM user WHERE user_id = ?", [$user_id]);
if (!$user) {
    sendResponse(["error" => "User not found"], 404);
}

// Prepare update query
$updates = [];
$params = [];

if (isset($data['username'])) {
    $username = trim($data['username']);
    $existingUsername = fetchOne($conn, "SELECT user_id FROM user WHERE username = ? AND user_id != ?", [$username, $user_id]);
    if ($existingUsername) {
        sendResponse(["error" => "Username already exists"], 409);
    }
    $updates[] = "username = ?";
    $params[] = $username;
}

if (isset($data['full_name'])) {
    $updates[] = "full_name = ?";
    $params[] = trim($data['full_name']);
}
if (isset($data['email'])) {
    $updates[] = "email = ?";
    $params[] = trim($data['email']);
}
if (isset($data['bio'])) {
    $updates[] = "bio = ?";
    $params[] = trim($data['bio']);
}
if (isset($data['avatar_url'])) {
    $updates[] = "avatar_url = ?";
    $params[] = trim($data['avatar_url']);
}

if (empty($updates)) {
    sendResponse(["error" => "No fields to update"], 400);
}

$updates[] = "updated_at = NOW()";
$params[] = $user_id;

$query = "UPDATE user SET " . implode(", ", $updates) . " WHERE user_id = ?";
$result = update($conn, $query, $params);

if (isset($result['error'])) {
    sendResponse($result, 500);
}

// Handle interests update
if (isset($data['interests']) && is_array($data['interests'])) {
    // Delete existing interests
    delete($conn, "DELETE FROM user_interest WHERE user_id = ?", [$user_id]);
    
    // Add new interests
    foreach ($data['interests'] as $interest_id) {
        insert($conn, "INSERT INTO user_interest (user_id, interest_id) VALUES (?, ?)", [$user_id, $interest_id]);
    }
}

// Get updated user
$updatedUser = fetchOne($conn, "SELECT user_id, username, full_name, email, bio, avatar_url, updated_at FROM user WHERE user_id = ?", [$user_id]);

// Get user interests
$interests = fetchAll($conn, "SELECT i.interest_id, i.name 
                              FROM interest i 
                              JOIN user_interest ui ON i.interest_id = ui.interest_id 
                              WHERE ui.user_id = ?", [$user_id]);

$updatedUser['interests'] = $interests;

sendResponse([
    "success" => true,
    "message" => "Profile updated successfully",
    "user" => $updatedUser
], 200);
?>
