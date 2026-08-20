<?php
// Helper functions for database operations

/**
 * Execute a SELECT query
 */
function fetchAll($conn, $query, $params = []) {
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        return ["error" => "Query preparation failed: " . $conn->error];
    }
    
    if (!empty($params)) {
        $types = str_repeat("s", count($params));
        $stmt->bind_param($types, ...$params);
    }
    
    if (!$stmt->execute()) {
        return ["error" => "Query execution failed: " . $stmt->error];
    }
    
    $result = $stmt->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    
    return $data;
}

/**
 * Execute a SELECT query and fetch one row
 */
function fetchOne($conn, $query, $params = []) {
    $result = fetchAll($conn, $query, $params);
    if (is_array($result) && !isset($result["error"])) {
        return $result[0] ?? null;
    }
    return $result;
}

/**
 * Execute an INSERT query
 */
function insert($conn, $query, $params = []) {
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        return ["error" => "Query preparation failed: " . $conn->error];
    }
    
    if (!empty($params)) {
        $types = str_repeat("s", count($params));
        $stmt->bind_param($types, ...$params);
    }
    
    if (!$stmt->execute()) {
        return ["error" => "Insert failed: " . $stmt->error];
    }
    
    $id = $conn->insert_id;
    $stmt->close();
    
    return ["success" => true, "id" => $id];
}

/**
 * Execute an UPDATE query
 */
function update($conn, $query, $params = []) {
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        return ["error" => "Query preparation failed: " . $conn->error];
    }
    
    if (!empty($params)) {
        $types = str_repeat("s", count($params));
        $stmt->bind_param($types, ...$params);
    }
    
    if (!$stmt->execute()) {
        return ["error" => "Update failed: " . $stmt->error];
    }
    
    $affected = $stmt->affected_rows;
    $stmt->close();
    
    return ["success" => true, "affected" => $affected];
}

/**
 * Execute a DELETE query
 */
function delete($conn, $query, $params = []) {
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        return ["error" => "Query preparation failed: " . $conn->error];
    }
    
    if (!empty($params)) {
        $types = str_repeat("s", count($params));
        $stmt->bind_param($types, ...$params);
    }
    
    if (!$stmt->execute()) {
        return ["error" => "Delete failed: " . $stmt->error];
    }
    
    $affected = $stmt->affected_rows;
    $stmt->close();
    
    return ["success" => true, "affected" => $affected];
}

/**
 * Hash password
 */
function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

/**
 * Verify password
 */
function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Generate JWT token (simple version)
 */
function generateToken($user_id, $username) {
    $secret = "your_secret_key_here"; // Change this to a strong secret
    $issuedAt = time();
    $expire = $issuedAt + (7 * 24 * 60 * 60); // 7 days
    
    $payload = [
        "user_id" => $user_id,
        "username" => $username,
        "iat" => $issuedAt,
        "exp" => $expire
    ];
    
    return base64_encode(json_encode($payload));
}

/**
 * Send JSON response
 */
function sendResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    echo json_encode($data);
    exit();
}

/**
 * Get request body
 */
function getRequestBody() {
    $input = file_get_contents('php://input');
    return json_decode($input, true);
}
?>
