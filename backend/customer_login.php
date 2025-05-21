<?php
session_start();
include("db.php");

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (!$email || !$password) {
    die("❌ Please fill all fields.");
}

$stmt = $conn->prepare("SELECT * FROM customers WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['customer_id'] = $user['id'];
    echo "✅ Login successful! Welcome " . htmlspecialchars($user['name']) . ".";
    header("Location: dashboard_customer.php");
} else {
    echo "❌ Invalid email or password.";
}
?>
