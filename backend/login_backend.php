<?php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        // Prepare and execute query securely
        $stmt = $conn->prepare("SELECT * FROM business_users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Successful login
            session_regenerate_id(true); // Prevent session fixation
            $_SESSION['business_id'] = $user['id']; // Better naming
            $_SESSION['business_name'] = $user['name'] ?? ''; // Optional

            header("Location: dashboard_business.php");
            exit;
        } else {
            // Invalid credentials
            header("Location: ../Business_login.html?error=1");
            exit;
        }
    } catch (PDOException $e) {
        // Log error internally
        error_log("Login Error: " . $e->getMessage());
        header("Location: ../Business_login.html?error=1");
        exit;
    }
} else {
    header("Location: ../Business_login.html");
    exit;
}
?>
