<?php
require_once '../../config.php';
require_once '../../includes/functions.php';

// Only allow DELETE requests
if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
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

// Delete blog
$result = delete($conn, "DELETE FROM blog WHERE blog_id = ?", [$blog_id]);

if (isset($result['error'])) {
    sendResponse($result, 500);
}

sendResponse([
    "success" => true,
    "message" => "Blog deleted successfully"
], 200);
?>
