<?php
require_once '../../config.php';
require_once '../../includes/functions.php';

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendResponse(["error" => "Method not allowed"], 405);
}

$query = isset($_GET['q']) ? trim($_GET['q']) : null;
$type = isset($_GET['type']) ? trim($_GET['type']) : 'all'; // all, blogs, users
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

if (!$query || strlen($query) < 2) {
    sendResponse(["error" => "Search query must be at least 2 characters"], 400);
}

$searchTerm = "%" . $query . "%";
$results = [];

// Search blogs
if ($type === 'all' || $type === 'blogs') {
    $blogs = fetchAll($conn, "SELECT b.blog_id, b.title, b.subtitle, b.body, b.cover_image_url, b.created_at, 
                              u.user_id, u.username, u.full_name, u.avatar_url 
                              FROM blog b 
                              JOIN user u ON b.user_id = u.user_id 
                              WHERE b.title LIKE ? OR b.subtitle LIKE ? OR b.body LIKE ? 
                              ORDER BY b.created_at DESC 
                              LIMIT ? OFFSET ?", [$searchTerm, $searchTerm, $searchTerm, (string)$limit, (string)$offset]);
    $results['blogs'] = $blogs;
}

// Search users
if ($type === 'all' || $type === 'users') {
    $users = fetchAll($conn, "SELECT user_id, username, full_name, bio, avatar_url 
                              FROM user 
                              WHERE username LIKE ? OR full_name LIKE ? OR bio LIKE ? 
                              ORDER BY full_name ASC 
                              LIMIT ? OFFSET ?", [$searchTerm, $searchTerm, $searchTerm, (string)$limit, (string)$offset]);
    $results['users'] = $users;
}

sendResponse([
    "success" => true,
    "query" => $query,
    "results" => $results
], 200);
?>
