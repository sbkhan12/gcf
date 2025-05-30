<?php
session_start();
include("db.php"); // Ensure this connects to your database correctly

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Use correct column: 'email'
$stmt = $conn->prepare("SELECT * FROM admin WHERE email = ?");
$stmt->execute([$email]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

if ($admin && password_verify($password, $admin['password'])) {
    $_SESSION['admin_id'] = $admin['id'];
    header("Location: dashboard_admin.php");
    exit();
} else {
    echo "❌ Invalid credentials.";
}
?>
