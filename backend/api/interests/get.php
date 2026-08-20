<?php
require_once '../../config.php';
require_once '../../includes/functions.php';

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendResponse(["error" => "Method not allowed"], 405);
}

// Get all interests
$interests = fetchAll($conn, "SELECT interest_id, name FROM interest ORDER BY name ASC", []);

if (isset($interests['error'])) {
    sendResponse($interests, 500);
}

sendResponse([
    "success" => true,
    "interests" => $interests
], 200);
?>
