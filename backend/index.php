<?php
require_once 'config.php';

$message = "InkFlow Blog Application - Backend API";
$endpoints = [
    "Authentication" => [
        "Sign Up" => "POST /api/auth/signup.php",
        "Login" => "POST /api/auth/login.php"
    ],
    "Blogs" => [
        "Create Blog" => "POST /api/blogs/create.php",
        "Get Blogs" => "GET /api/blogs/get.php",
        "Update Blog" => "PUT /api/blogs/update.php",
        "Delete Blog" => "DELETE /api/blogs/delete.php"
    ],
    "Users" => [
        "Get Profile" => "GET /api/users/profile.php",
        "Update Profile" => "PUT /api/users/update-profile.php"
    ],
    "Interests" => [
        "Get All Interests" => "GET /api/interests/get.php"
    ],
    "Search" => [
        "Global Search" => "GET /api/search/index.php"
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InkFlow Backend API</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            max-width: 900px;
            width: 100%;
            padding: 40px;
        }
        h1 {
            color: #667eea;
            margin-bottom: 10px;
            text-align: center;
        }
        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 40px;
        }
        .endpoint-section {
            margin-bottom: 30px;
        }
        .endpoint-section h2 {
            color: #764ba2;
            font-size: 18px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }
        .endpoint {
            background: #f8f9fa;
            padding: 12px;
            margin-bottom: 10px;
            border-left: 4px solid #667eea;
            border-radius: 4px;
        }
        .endpoint-name {
            color: #333;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .endpoint-method {
            font-family: 'Courier New', monospace;
            color: #666;
            font-size: 13px;
        }
        .method-post {
            color: #0066cc;
        }
        .method-get {
            color: #28a745;
        }
        .method-put {
            color: #ffc107;
        }
        .method-delete {
            color: #dc3545;
        }
        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin-bottom: 30px;
            border-radius: 4px;
            color: #0c5aa0;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
            font-size: 13px;
        }
        a {
            color: #667eea;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo $message; ?></h1>
        <p class="subtitle">RESTful API for Blog Management</p>

        <div class="info-box">
            <strong>📖 Documentation:</strong> See <a href="API_DOCUMENTATION.md" target="_blank">API_DOCUMENTATION.md</a> for detailed endpoint documentation.
        </div>

        <?php foreach ($endpoints as $category => $methods): ?>
            <div class="endpoint-section">
                <h2><?php echo $category; ?></h2>
                <?php foreach ($methods as $name => $method): ?>
                    <div class="endpoint">
                        <div class="endpoint-name"><?php echo $name; ?></div>
                        <div class="endpoint-method">
                            <?php
                                $parts = explode(' ', $method);
                                $httpMethod = $parts[0];
                                $path = $parts[1];
                                $methodClass = 'method-' . strtolower($httpMethod);
                            ?>
                            <span class="<?php echo $methodClass; ?>"><?php echo $httpMethod; ?></span> 
                            <code><?php echo $path; ?></code>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>

        <div class="footer">
            <p>InkFlow Blog Application &copy; 2024</p>
            <p>Base URL: <code>http://localhost/Blog-Application/backend/</code></p>
        </div>
    </div>
</body>
</html>
