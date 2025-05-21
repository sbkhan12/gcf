<?php
include 'db.php';
session_start();

// Check if business is logged in
if (!isset($_SESSION['business_id'])) {
    header('Location: ../Business_login.html');
    exit();
}

$business_id = $_SESSION['business_id'];

// Fetch salon name
$bizStmt = $conn->prepare("SELECT salon FROM business_users WHERE id = ?");
$bizStmt->execute([$business_id]);
$businessName = $bizStmt->fetch(PDO::FETCH_ASSOC)['salon'] ?? 'Owner';

// Today's appointments count
$todayStmt = $conn->prepare("SELECT COUNT(*) AS count FROM bookings WHERE salon_id = ? AND booking_date = CURDATE()");
$todayStmt->execute([$business_id]);
$appointmentsToday = $todayStmt->fetch(PDO::FETCH_ASSOC)['count'];

// Upcoming bookings count
$upcomingStmt = $conn->prepare("SELECT COUNT(*) AS count FROM bookings WHERE salon_id = ? AND booking_date > CURDATE()");
$upcomingStmt->execute([$business_id]);
$upcomingBookings = $upcomingStmt->fetch(PDO::FETCH_ASSOC)['count'];

// New messages count (assuming messages table uses business_id)
$messagesStmt = $conn->prepare("SELECT COUNT(*) AS count FROM messages WHERE business_id = ? AND is_read = 0");
$messagesStmt->execute([$business_id]);
$newMessages = $messagesStmt->fetch(PDO::FETCH_ASSOC)['count'];

// Fetch bookings with service title for display
$bookingsStmt = $conn->prepare("
    SELECT b.id, b.customer_name, b.booking_date, b.booking_time, b.status, s.title AS service_title
    FROM bookings b
    LEFT JOIN services s ON b.service_id = s.id
    WHERE b.salon_id = ?
    ORDER BY b.booking_date DESC, b.booking_time DESC
");
$bookingsStmt->execute([$business_id]);
$bookings = $bookingsStmt->fetchAll(PDO::FETCH_ASSOC);

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $booking_id = intval($_POST['booking_id']);
    $new_status = $_POST['status'];

    $valid_statuses = ['Pending', 'Confirmed', 'Cancelled', 'Completed'];
    if (in_array($new_status, $valid_statuses)) {
        $updateStmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ? AND salon_id = ?");
        $updateStmt->execute([$new_status, $booking_id, $business_id]);
        header("Location: dashboard_business.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Business Dashboard - Glam Connect</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">

  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md">
      <div class="p-6">
        <h1 class="text-2xl font-bold">Business Panel</h1>
        <p class="text-sm text-gray-500">Welcome, <?= htmlspecialchars($businessName) ?></p>
      </div>
      <nav class="mt-6">
        <a href="dashboard_business.php" class="block px-6 py-2.5 hover:bg-gray-200">Dashboard</a>
        <a href="dashboard_business.php" class="block px-6 py-2.5 hover:bg-gray-200">My Appointments</a>
        <a href="../index2.php" class="block px-6 py-2.5 hover:bg-gray-200">Service Listings</a>
        <a href="reviews.php" class="block px-6 py-2.5 hover:bg-gray-200">Customer Reviews</a>
        <a href="logout.php" class="block px-6 py-2.5 text-red-600 hover:bg-red-100">Logout</a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-10">
      <h2 class="text-3xl font-semibold mb-6">My Salon Dashboard</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-6 rounded-lg shadow-md">
          <h3 class="text-lg font-medium">Today’s Appointments</h3>
          <p class="text-3xl mt-2 text-blue-600"><?= $appointmentsToday ?></p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
          <h3 class="text-lg font-medium">Upcoming Bookings</h3>
          <p class="text-3xl mt-2 text-purple-600"><?= $upcomingBookings ?></p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
          <h3 class="text-lg font-medium">New Messages</h3>
          <p class="text-3xl mt-2 text-green-600"><?= $newMessages ?></p>
        </div>
      </div>

      <h3 class="text-2xl font-semibold mb-4">Manage Bookings</h3>
      <div class="overflow-x-auto bg-white rounded-lg shadow-md p-6">
        <table class="min-w-full table-auto border-collapse border border-gray-200">
          <thead>
            <tr class="bg-gray-100">
              <th class="border border-gray-300 px-4 py-2 text-left">Customer</th>
              <th class="border border-gray-300 px-4 py-2 text-left">Service</th>
              <th class="border border-gray-300 px-4 py-2 text-left">Date</th>
              <th class="border border-gray-300 px-4 py-2 text-left">Time</th>
              <th class="border border-gray-300 px-4 py-2 text-left">Status</th>
              <th class="border border-gray-300 px-4 py-2 text-left">Update Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($bookings as $booking): ?>
              <tr class="border border-gray-300">
                <td class="px-4 py-2"><?= htmlspecialchars($booking['customer_name']) ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($booking['service_title'] ?? 'Unknown Service') ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($booking['booking_date']) ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($booking['booking_time']) ?></td>
                <td class="px-4 py-2 font-semibold 
                  <?php 
                    switch($booking['status']) {
                      case 'Confirmed': echo 'text-green-600'; break;
                      case 'Pending': echo 'text-yellow-500'; break;
                      case 'Cancelled': echo 'text-red-600'; break;
                      case 'Completed': echo 'text-blue-600'; break;
                      default: echo 'text-gray-600'; 
                    } 
                  ?>">
                  <?= htmlspecialchars($booking['status']) ?>
                </td>
                <td class="px-4 py-2">
                  <form method="POST" class="flex items-center space-x-2">
                    <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                    <select name="status" class="border rounded px-2 py-1">
                      <?php
                      $statuses = ['Pending', 'Confirmed', 'Cancelled', 'Completed'];
                      foreach ($statuses as $status):
                      ?>
                        <option value="<?= $status ?>" <?= ($booking['status'] === $status) ? 'selected' : '' ?>><?= $status ?></option>
                      <?php endforeach; ?>
                    </select>
                    <button type="submit" name="update_status" class="bg-black text-white px-3 py-1 rounded hover:bg-gray-800">Update</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if (empty($bookings)): ?>
              <tr>
                <td colspan="6" class="px-4 py-6 text-center text-gray-500">No bookings found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </main>
  </div>

</body>
</html>
