<?php
include 'db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Fetch all bookings with salon & service info
$stmt = $conn->prepare("
  SELECT b.id, b.booking_date, b.booking_time, b.customer_name, b.status,
         bu.salon AS salon_name, s.title AS service_title
  FROM bookings b
  JOIN business_users bu ON b.salon_id = bu.id
  JOIN services s ON b.service_id = s.id
  ORDER BY b.booking_date DESC, b.booking_time DESC
");
$stmt->execute();
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Manage Appointments - Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

  <!-- Header -->
  <header class="bg-white shadow">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
      <h1 class="text-2xl font-bold">GlamConnect</h1>
      <nav>
        <ul class="flex space-x-6 text-gray-700">
          <li><a href="dashboard_admin.php" class="hover:underline">Dashboard</a></li>
          <li><a href="manage_customers.php" class="hover:underline">Customers</a></li>
          <!-- <li><a href="manage_appointments.php" class="font-semibold text-blue-600">Appointments</a></li> -->
        </ul>
      </nav>
    </div>
  </header>

  <!-- Main Content -->
  <main class="max-w-6xl mx-auto mt-10 px-4 sm:px-8">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Manage Appointments</h2>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">ID</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Salon</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Service</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Customer</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Date</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Time</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Status</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <?php foreach ($appointments as $app): ?>
          <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 text-sm"><?= htmlspecialchars($app['id']) ?></td>
            <td class="px-6 py-4 text-sm"><?= htmlspecialchars($app['salon_name']) ?></td>
            <td class="px-6 py-4 text-sm"><?= htmlspecialchars($app['service_title']) ?></td>
            <td class="px-6 py-4 text-sm"><?= htmlspecialchars($app['customer_name']) ?></td>
            <td class="px-6 py-4 text-sm"><?= htmlspecialchars($app['booking_date']) ?></td>
            <td class="px-6 py-4 text-sm"><?= htmlspecialchars($app['booking_time']) ?></td>
            <td class="px-6 py-4 text-sm">
              <?php
                $statusColor = [
                  'Pending' => 'text-yellow-600',
                  'Confirmed' => 'text-green-600',
                  'Cancelled' => 'text-red-600',
                ][$app['status']] ?? 'text-gray-600';
              ?>
              <span class="font-semibold <?= $statusColor ?>"><?= htmlspecialchars($app['status']) ?></span>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>

  <!-- Footer -->
  <footer class="bg-black text-white mt-20 py-10 px-10">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center md:text-left">
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
