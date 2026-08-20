<?php
require_once '../../config.php';
require_once '../../includes/functions.php';

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendResponse(["error" => "Method not allowed"], 405);
}

$blog_id = isset($_GET['blog_id']) ? trim($_GET['blog_id']) : null;
$user_id = isset($_GET['user_id']) ? trim($_GET['user_id']) : null;
$search = isset($_GET['search']) ? trim($_GET['search']) : null;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

// Get single blog
if ($blog_id) {
    $blog = fetchOne($conn, "SELECT b.*, u.username, u.full_name, u.avatar_url 
                             FROM blog b 
                             JOIN user u ON b.user_id = u.user_id 
                             WHERE b.blog_id = ?", [$blog_id]);
    if (!$blog) {
        sendResponse(["error" => "Blog not found"], 404);
    }
    sendResponse(["success" => true, "blog" => $blog], 200);
}

// Get user's blogs
if ($user_id) {
    $blogs = fetchAll($conn, "SELECT b.*, u.username, u.full_name, u.avatar_url 
                              FROM blog b 
                              JOIN user u ON b.user_id = u.user_id 
                              WHERE b.user_id = ? 
                              ORDER BY b.created_at DESC 
                              LIMIT ? OFFSET ?", [$user_id, (string)$limit, (string)$offset]);
    sendResponse(["success" => true, "blogs" => $blogs], 200);
}

// Search blogs
if ($search) {
    $searchTerm = "%" . $search . "%";
    $blogs = fetchAll($conn, "SELECT b.*, u.username, u.full_name, u.avatar_url 
                              FROM blog b 
                              JOIN user u ON b.user_id = u.user_id 
                              WHERE b.title LIKE ? OR b.subtitle LIKE ? OR b.body LIKE ? 
                              ORDER BY b.created_at DESC 
                              LIMIT ? OFFSET ?", [$searchTerm, $searchTerm, $searchTerm, (string)$limit, (string)$offset]);
    sendResponse(["success" => true, "blogs" => $blogs], 200);
}

// Get all blogs (latest first)
$blogs = fetchAll($conn, "SELECT b.*, u.username, u.full_name, u.avatar_url 
                          FROM blog b 
                          JOIN user u ON b.user_id = u.user_id 
                          ORDER BY b.created_at DESC 
                          LIMIT ? OFFSET ?", [(string)$limit, (string)$offset]);

sendResponse(["success" => true, "blogs" => $blogs], 200);
?>
