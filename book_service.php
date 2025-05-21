<?php
session_start();
include 'db.php';

// Restrict access to logged-in customers
if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_login.html");
    exit;
}

$customer_id = $_SESSION['customer_id'];

// Get customer name from database (instead of session)
$customerStmt = $conn->prepare("SELECT name FROM customers WHERE id = ?");
$customerStmt->execute([$customer_id]);
$customerData = $customerStmt->fetch(PDO::FETCH_ASSOC);

if (!$customerData) {
    echo "Customer not found.";
    exit;
}
$customer_name = $customerData['name'];

// Validate service and salon
$service_id = isset($_POST['service_id']) ? intval($_POST['service_id']) : 0;
$salon_id = isset($_POST['salon_id']) ? intval($_POST['salon_id']) : 0;

if (!$service_id || !$salon_id) {
    echo "Invalid booking request.";
    exit;
}

// Get service and salon info
$stmt = $conn->prepare("
    SELECT s.*, b.salon 
    FROM services s 
    JOIN business_users b ON s.business_id = b.id 
    WHERE s.id = ?
");
$stmt->execute([$service_id]);
$service = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$service) {
    echo "Service not found!";
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['confirm_booking'])) {
    $booking_date = $_POST['booking_date'] ?? '';
    $booking_time = $_POST['booking_time'] ?? '';

    if ($booking_date && $booking_time) {
        $insert = $conn->prepare("
            INSERT INTO bookings 
            (service_id, salon_id, customer_id, customer_name, booking_date, booking_time, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");
        $insert->execute([
            $service_id,
            $salon_id,
            $customer_id,
            $customer_name,
            $booking_date,
            $booking_time
        ]);

        echo "<script>alert('Booking successful!'); window.location.href='index.php';</script>";
        exit;
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Service - <?= htmlspecialchars($service['title']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 py-10">
<div class="max-w-xl mx-auto bg-white p-8 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Book <?= htmlspecialchars($service['title']) ?> at <?= htmlspecialchars($service['salon']) ?></h2>

    <?php if (!empty($error)): ?>
        <p class="text-red-600 mb-4"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="service_id" value="<?= $service_id ?>">
        <input type="hidden" name="salon_id" value="<?= $salon_id ?>">

        <div class="mb-3">
            <label class="block mb-1">Date:</label>
            <input type="date" name="booking_date" required class="w-full border px-3 py-2 rounded">
        </div>

        <div class="mb-3">
            <label class="block mb-1">Time:</label>
            <input type="time" name="booking_time" required class="w-full border px-3 py-2 rounded">
        </div>

        <button type="submit" name="confirm_booking" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800">
            Confirm Booking
        </button>
    </form>
</div>
</body>
</html>
