<?php
session_start();
include 'db.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: customer_login.html");
    exit;
}

$customer_id = $_SESSION['customer_id'];

// Fetch real bookings
$stmt = $conn->prepare("
    SELECT b.booking_date, b.booking_time, b.status, 
           s.title AS service_title, 
           bu.salon AS salon_name
    FROM bookings b
    JOIN services s ON b.service_id = s.id
    JOIN business_users bu ON b.salon_id = bu.id
    WHERE b.customer_id = ?
    ORDER BY b.booking_date DESC, b.booking_time DESC
");
$stmt->execute([$customer_id]);
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Customer Dashboard - Glam Connect</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">

  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md">
      <div class="p-6">
        <h1 class="text-2xl font-bold">Customer Panel</h1>
        <p class="text-sm text-gray-500">Welcome back!</p>
      </div>
      <nav class="mt-6">
        <a href="#" class="block px-6 py-2.5 hover:bg-gray-200 font-semibold">My Appointments</a>
        <a href="../index.php" class="block px-6 py-2.5 hover:bg-gray-200">Explore Salons</a>
        <a href="#" class="block px-6 py-2.5 hover:bg-gray-200">Favorite Salons</a>
        <a href="#" class="block px-6 py-2.5 hover:bg-gray-200">Account Settings</a>
        <a href="logout.php" class="block px-6 py-2.5 text-red-600 hover:bg-red-100">Logout</a>
      </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-10">
      <h2 class="text-3xl font-semibold mb-6">Your Appointments</h2>
      <div class="bg-white rounded-lg shadow-md p-6">
        <?php if ($appointments): ?>
        <table class="min-w-full table-auto">
          <thead class="bg-gray-100">
            <tr>
              <th class="px-6 py-3 text-left text-sm font-medium">Salon</th>
              <th class="px-6 py-3 text-left text-sm font-medium">Service</th>
              <th class="px-6 py-3 text-left text-sm font-medium">Date</th>
              <th class="px-6 py-3 text-left text-sm font-medium">Time</th>
              <th class="px-6 py-3 text-left text-sm font-medium">Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($appointments as $app): ?>
              <tr class="border-b">
                <td class="px-6 py-4"><?= htmlspecialchars($app['salon_name']) ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($app['service_title']) ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($app['booking_date']) ?></td>
                <td class="px-6 py-4"><?= htmlspecialchars($app['booking_time']) ?></td>
                <td class="px-6 py-4 font-semibold
                    <?= $app['status'] === 'Confirmed' ? 'text-green-600' : 'text-yellow-500' ?>">
                    <?= htmlspecialchars($app['status'] ?? 'Pending') ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php else: ?>
          <p class="text-gray-600">No appointments found.</p>
        <?php endif; ?>
      </div>
    </main>
  </div>

</body>
</html>
