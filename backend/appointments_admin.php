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
<body class="bg-gray-100 p-8">
  <h1 class="text-3xl mb-6 font-bold">Manage Appointments</h1>
  <table class="min-w-full bg-white rounded shadow">
    <thead>
      <tr>
        <th class="px-6 py-3 border-b text-left">ID</th>
        <th class="px-6 py-3 border-b text-left">Salon</th>
        <th class="px-6 py-3 border-b text-left">Service</th>
        <th class="px-6 py-3 border-b text-left">Customer</th>
        <th class="px-6 py-3 border-b text-left">Date</th>
        <th class="px-6 py-3 border-b text-left">Time</th>
        <th class="px-6 py-3 border-b text-left">Status</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($appointments as $app): ?>
        <tr class="hover:bg-gray-100">
          <td class="px-6 py-4 border-b"><?= htmlspecialchars($app['id']) ?></td>
          <td class="px-6 py-4 border-b"><?= htmlspecialchars($app['salon_name']) ?></td>
          <td class="px-6 py-4 border-b"><?= htmlspecialchars($app['service_title']) ?></td>
          <td class="px-6 py-4 border-b"><?= htmlspecialchars($app['customer_name']) ?></td>
          <td class="px-6 py-4 border-b"><?= htmlspecialchars($app['booking_date']) ?></td>
          <td class="px-6 py-4 border-b"><?= htmlspecialchars($app['booking_time']) ?></td>
          <td class="px-6 py-4 border-b">
            <?php
              $statusColor = [
                'Pending' => 'text-yellow-500',
                'Confirmed' => 'text-green-600',
                'Cancelled' => 'text-red-600',
              ][$app['status']] ?? 'text-gray-600';
            ?>
            <span class="<?= $statusColor ?> font-semibold"><?= htmlspecialchars($app['status']) ?></span>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
