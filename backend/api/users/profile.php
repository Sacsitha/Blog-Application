<?php
require_once '../../config.php';
require_once '../../includes/functions.php';

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendResponse(["error" => "Method not allowed"], 405);
}

$user_id = isset($_GET['user_id']) ? trim($_GET['user_id']) : null;
$username = isset($_GET['username']) ? trim($_GET['username']) : null;

// Get by user_id
if ($user_id) {
    $user = fetchOne($conn, "SELECT user_id, username, full_name, email, bio, avatar_url, created_at FROM user WHERE user_id = ?", [$user_id]);
    if (!$user) {
        sendResponse(["error" => "User not found"], 404);
    }
    
    // Get user interests
    $interests = fetchAll($conn, "SELECT i.interest_id, i.name 
                                  FROM interest i 
                                  JOIN user_interest ui ON i.interest_id = ui.interest_id 
                                  WHERE ui.user_id = ?", [$user_id]);
    
    $user['interests'] = $interests;
    
    sendResponse(["success" => true, "user" => $user], 200);
}

// Get by username
if ($username) {
    $user = fetchOne($conn, "SELECT user_id, username, full_name, email, bio, avatar_url, created_at FROM user WHERE username = ?", [$username]);
    if (!$user) {
        sendResponse(["error" => "User not found"], 404);
    }
    
    // Get user interests
    $interests = fetchAll($conn, "SELECT i.interest_id, i.name 
                                  FROM interest i 
                                  JOIN user_interest ui ON i.interest_id = ui.interest_id 
                                  WHERE ui.user_id = ?", [$user['user_id']]);
    
    $user['interests'] = $interests;
    
    sendResponse(["success" => true, "user" => $user], 200);
}

sendResponse(["error" => "user_id or username required"], 400);
?>
