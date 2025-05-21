<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $owner = htmlspecialchars(trim($_POST['owner']));
    $salon = htmlspecialchars(trim($_POST['salon']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);
    $cpassword = trim($_POST['cpassword']);
    $address = htmlspecialchars(trim($_POST['address']));
    $city = htmlspecialchars(trim($_POST['city']));

    // Validate passwords match
    if ($password !== $cpassword) {
        die("Passwords do not match!");
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Database connection
    $servername = "localhost";
    $username = "root"; // Replace with your DB username
    $dbpassword = "";   // Replace with your DB password
    $dbname = "saloons"; // Replace with your DB name

    $conn = new mysqli($servername, $username, $dbpassword, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert data into database
    $sql = "INSERT INTO users (owner_name, salon_name, email, password, address, city) 
            VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $owner, $salon, $email, $hashed_password, $address, $city);

    if ($stmt->execute()) {
        echo "Account created successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close connection
    $stmt->close();
    $conn->close();
}
?>
