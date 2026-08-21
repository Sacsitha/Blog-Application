
<?php
$host = "sql204.infinityfree.com";
$username = "if0_42711873";
$password = "Sacsi7453";
$dbname = "if0_42711873_blog_db";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>