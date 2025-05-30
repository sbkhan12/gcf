<?php
include("db.php");

$owner = $_POST['owner'] ?? '';
$salon = $_POST['salon'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$cpassword = $_POST['cpassword'] ?? '';
$address = $_POST['address'] ?? '';
$city = $_POST['city'] ?? '';

// Password match check
if ($password !== $cpassword) {
    die("❌ Passwords do not match.");
}

// Email already registered check
$stmt = $conn->prepare("SELECT id FROM business_users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    die("❌ Email already registered.");
}

// Image upload handling
$imagePath = '';
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../uploads/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $imageTmp = $_FILES['image']['tmp_name'];
    $imageName = basename($_FILES['image']['name']);
    $uniqueName = uniqid() . '_' . $imageName;
    $targetPath = $uploadDir . $uniqueName;

    // Validate file type (optional)
    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($_FILES['image']['type'], $allowedTypes)) {
        die("❌ Only JPG, PNG, and WEBP images are allowed.");
    }

    // Move uploaded file
    if (move_uploaded_file($imageTmp, $targetPath)) {
        $imagePath = 'uploads/' . $uniqueName; // Save relative path
    } else {
        die("❌ Failed to upload image.");
    }
} else {
    die("❌ Please upload a salon image.");
}

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert into DB with image
$stmt = $conn->prepare("INSERT INTO business_users (owner, salon, email, password, address, city, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
$success = $stmt->execute([$owner, $salon, $email, $hashed_password, $address, $city, $imagePath]);

if ($success) {
    header("Location: ../business_login.html");
    exit;
} else {
    echo "❌ Signup failed. Please try again.";
}
?>
