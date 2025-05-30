<?php
include("db.php");

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$cpassword = $_POST['cpassword'] ?? '';
$phone = $_POST['phone'] ?? '';
$city = $_POST['city'] ?? '';

if ($password !== $cpassword) {
    die("❌ Passwords do not match.");
}

// Check if email exists
$stmt = $conn->prepare("SELECT id FROM customers WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    die("❌ Email already registered.");
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO customers (name, email, password, phone, city) VALUES (?, ?, ?, ?, ?)");
if ($stmt->execute([$name, $email, $hashed_password, $phone, $city])) {
    echo "✅ Customer account created successfully.";
    // Optionally redirect to login page
    header("Location: ../customer_login.html");
} else {
    echo "❌ Signup failed. Try again.";
}
?>
