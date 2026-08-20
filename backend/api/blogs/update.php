<?php
require_once '../../config.php';
require_once '../../includes/functions.php';

// Only allow PUT requests
if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
    sendResponse(["error" => "Method not allowed"], 405);
}

$data = getRequestBody();

// Validate input
if (!isset($data['blog_id']) || !isset($data['user_id'])) {
    sendResponse(["error" => "Missing blog_id or user_id"], 400);
}

$blog_id = trim($data['blog_id']);
$user_id = trim($data['user_id']);

// Verify blog exists and user owns it
$blog = fetchOne($conn, "SELECT * FROM blog WHERE blog_id = ?", [$blog_id]);
if (!$blog) {
    sendResponse(["error" => "Blog not found"], 404);
}

if ($blog['user_id'] != $user_id) {
    sendResponse(["error" => "Unauthorized"], 403);
}

// Prepare update query
$updates = [];
$params = [];

if (isset($data['title'])) {
    $updates[] = "title = ?";
    $params[] = trim($data['title']);
}
if (isset($data['subtitle'])) {
    $updates[] = "subtitle = ?";
    $params[] = trim($data['subtitle']);
}
if (isset($data['body'])) {
    $updates[] = "body = ?";
    $params[] = trim($data['body']);
}
if (isset($data['cover_image_url'])) {
    $updates[] = "cover_image_url = ?";
    $params[] = trim($data['cover_image_url']);
}

if (empty($updates)) {
    sendResponse(["error" => "No fields to update"], 400);
}

$updates[] = "updated_at = NOW()";
$params[] = $blog_id;

$query = "UPDATE blog SET " . implode(", ", $updates) . " WHERE blog_id = ?";
$result = update($conn, $query, $params);

if (isset($result['error'])) {
    sendResponse($result, 500);
}

// Get updated blog
$updatedBlog = fetchOne($conn, "SELECT * FROM blog WHERE blog_id = ?", [$blog_id]);

sendResponse([
    "success" => true,
    "message" => "Blog updated successfully",
    "blog" => $updatedBlog
], 200);
?>
