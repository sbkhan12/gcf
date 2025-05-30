<?php
session_start();
include 'db.php';

// Restrict access to logged-in customers
if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_login.html");
    exit;
}

$customer_id = $_SESSION['customer_id'];

// Get customer name
$customerStmt = $conn->prepare("SELECT name FROM customers WHERE id = ?");
$customerStmt->execute([$customer_id]);
$customerData = $customerStmt->fetch(PDO::FETCH_ASSOC);
if (!$customerData) {
    echo "Customer not found.";
    exit;
}
$customer_name = $customerData['name'];

// Validate service and salon
$service_id = isset($_POST['service_id']) ? intval($_POST['service_id']) : (isset($_GET['service_id']) ? intval($_GET['service_id']) : 0);
$salon_id = isset($_POST['salon_id']) ? intval($_POST['salon_id']) : (isset($_GET['salon_id']) ? intval($_GET['salon_id']) : 0);
if (!$service_id || !$salon_id) {
    echo "Invalid booking request.";
    exit;
}

// Get full service & salon details
$stmt = $conn->prepare("
   SELECT s.id as service_id, s.title, s.description, s.price, s.image,
           b.id as salon_id, b.salon as salon_name, b.address
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

// Handle booking submission
$booking_success = false;
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

        $booking_success = true;
        $booked_time = $booking_time;
        $booked_date = $booking_date;
    } else {
        $error = "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Services - <?= htmlspecialchars($salon['salon']) ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <header class="shadow-xl bg-white">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
      <h1 class="text-2xl font-bold">GlamConnect</h1>
      <nav>
        <ul class="flex gap-6">
          <li><a href="index.php" class="hover:underline">Home</a></li>
          <li><a href="about.html" class="hover:underline">About</a></li>
          <li><a href="navigation.html" class="hover:underline">Login</a></li>
          <li><a href="business_signup.html" class="hover:underline">List your Business</a></li>
        </ul>
      </nav>
    </div>
  </header> <br>
<body class="bg-gray-100 py-10">
    
<div class="max-w-3xl mx-auto bg-white p-8 rounded shadow">
    <?php if ($booking_success): ?>
        <h2 class="text-2xl font-bold mb-4 text-green-600">Booking Confirmed!</h2>
        <p class="mb-4">Thank you, <strong><?= htmlspecialchars($customer_name) ?></strong>. Your booking has been successfully scheduled.</p>
        <div class="bg-gray-50 p-4 rounded border">
            <p><strong>Service:</strong> <?= htmlspecialchars($service['title']) ?></p>
            <p><strong>Salon:</strong> <?= htmlspecialchars($service['salon_name']) ?></p>
            <p><strong>Date:</strong> <?= htmlspecialchars($booked_date) ?></p>
            <p><strong>Time:</strong> <?= htmlspecialchars($booked_time) ?></p>
            <p><strong>Price:</strong> $<?= htmlspecialchars($service['price']) ?></p>
        </div>
        <a href="backend/dashboard_customer.php" class="mt-6 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Go to Dashboard
        </a>
    <?php else: ?>
        <h2 class="text-xl font-bold mb-4">Book: <?= htmlspecialchars($service['title']) ?> @ <?= htmlspecialchars($service['salon_name']) ?></h2>

        <!-- Service Info -->
        <div class="bg-gray-50 p-4 rounded mb-4">
            <?php if (!empty($service['image'])): ?>
                <img src="<?= htmlspecialchars($service['image']) ?>" alt="<?= htmlspecialchars($service['title']) ?>" class="w-full max-h-64 object-cover rounded mb-3">
            <?php endif; ?>
            <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($service['description'])) ?></p>
            <p><strong>Price:</strong> $<?= htmlspecialchars($service['price']) ?></p>
            <p><strong>Address:</strong> <?= htmlspecialchars($service['address']) ?></p>
        </div>

        <?php if (!empty($error)): ?>
            <p class="text-red-600 mb-4"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <!-- Booking Form -->
        <form method="POST">
            <input type="hidden" name="service_id" value="<?= $service_id ?>">
            <input type="hidden" name="salon_id" value="<?= $salon_id ?>">

            <div class="mb-4">
                <label class="block mb-1">Date:</label>
                <input type="date" name="booking_date" required class="w-full border px-3 py-2 rounded">
            </div>

            <div class="mb-4">
                <label class="block mb-1">Time:</label>
                <input type="time" name="booking_time" required class="w-full border px-3 py-2 rounded">
            </div>

            <button type="submit" name="confirm_booking" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800">
                Confirm Booking
            </button>
        </form>
    <?php endif; ?>
</div>
<footer class="bg-black text-white mt-20 py-10 px-10">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <div>
        <h4 class="font-bold mb-2">About GlamConnect</h4>
        <ul>
          <li><a href="#" class="hover:underline">Careers</a></li>
          <li><a href="#" class="hover:underline">Career Support</a></li>
        </ul>
      </div>
      <div>
        <h4 class="font-bold mb-2">For Business</h4>
        <ul>
          <li><a href="#" class="hover:underline">For Partners</a></li>
          <li><a href="#" class="hover:underline">Support</a></li>
          <li><a href="#" class="hover:underline">Status</a></li>
        </ul>
      </div>
      <div>
        <h4 class="font-bold mb-2">Social Media</h4>
        <ul>
          <li><a href="#" class="hover:underline">Facebook</a></li>
          <li><a href="#" class="hover:underline">Instagram</a></li>
          <li><a href="#" class="hover:underline">Twitter</a></li>
        </ul>
      </div>

    </div>
  </footer>
</body>
</html>
