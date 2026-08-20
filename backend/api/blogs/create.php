<?php
require_once '../../config.php';
require_once '../../includes/functions.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendResponse(["error" => "Method not allowed"], 405);
}

$data = getRequestBody();

// Validate input
if (!isset($data['user_id']) || !isset($data['title']) || !isset($data['body'])) {
    sendResponse(["error" => "Missing required fields (user_id, title, body)"], 400);
}

$user_id = trim($data['user_id']);
$title = trim($data['title']);
$subtitle = isset($data['subtitle']) ? trim($data['subtitle']) : '';
$body = trim($data['body']);
$cover_image_url = isset($data['cover_image_url']) ? trim($data['cover_image_url']) : '';
$tags = isset($data['tags']) ? trim($data['tags']) : '';

// Validate user exists
$user = fetchOne($conn, "SELECT user_id FROM user WHERE user_id = ?", [$user_id]);
if (!$user) {
    sendResponse(["error" => "User not found"], 404);
}

// Insert blog
$query = "INSERT INTO blog (user_id, title, subtitle, body, cover_image_url, created_at, updated_at) 
          VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
$result = insert($conn, $query, [$user_id, $title, $subtitle, $body, $cover_image_url]);

if (isset($result['error'])) {
    sendResponse($result, 500);
}

// Get the created blog
$blog = fetchOne($conn, "SELECT * FROM blog WHERE blog_id = ?", [(string)$result['id']]);

sendResponse([
    "success" => true,
    "message" => "Blog created successfully",
    "blog" => $blog
], 201);
?>
