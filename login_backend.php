<?php
// Start a session
session_start();

// Include database connection
include('db_connection.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email and password are provided
    if (empty($email) || empty($password)) {
        echo "Email and password are required.";
        exit;
    }

    // Check the email in the database
    $sql = "SELECT * FROM users WHERE email = :email";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bindParam(':email', $email, PDO::PARAM_STR); // Use bindParam() for PDO
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch associative array

        // Check if user exists
        if ($result) {
            // Verify the password
            if (password_verify($password, $result['password'])) {
                // Password is correct, create a session
                $_SESSION['user_id'] = $result['id'];
                $_SESSION['email'] = $result['email'];

                // Redirect to the dashboard or main page
                header("Location: homepage.html");
                exit;
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "No user found with this email.";
        }

        $stmt->close();
    } else {
        echo "Database query failed.";
    }

    $conn = null; // Close the PDO connection
} else {
    echo "Invalid request.";
}
?>
