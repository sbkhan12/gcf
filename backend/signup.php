<?php
include("db.php");

$owner = $_POST['owner'] ?? '';
$salon = $_POST['salon'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$cpassword = $_POST['cpassword'] ?? '';
$address = $_POST['address'] ?? '';
$city = $_POST['city'] ?? '';

if ($password !== $cpassword) {
    die("❌ Passwords do not match.");
}

// Check if email already exists
$stmt = $conn->prepare("SELECT id FROM business_users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    die("❌ Email already registered.");
}

// Hash the password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert new user
$stmt = $conn->prepare("INSERT INTO business_users (owner, salon, email, password, address, city) VALUES (?, ?, ?, ?, ?, ?)");
$success = $stmt->execute([$owner, $salon, $email, $hashed_password, $address, $city]);

if ($success) {
    echo "✅ Signup successful! You can now log in.";
     header("Location: ../business_login.html");
} else {
    echo "❌ Signup failed. Please try again.";
}
?>