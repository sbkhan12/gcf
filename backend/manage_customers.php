<?php
include 'db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Corrected column name: 'name' instead of 'customer_name'
$stmt = $conn->query("SELECT id, name, email, city, created_at FROM customers ORDER BY created_at DESC");
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Manage Customers - Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
  <h1 class="text-3xl mb-6 font-bold">Manage Customers</h1>
  <table class="min-w-full bg-white rounded shadow">
    <thead>
      <tr>
        <th class="px-6 py-3 border-b text-left">ID</th>
        <th class="px-6 py-3 border-b text-left">Name</th>
        <th class="px-6 py-3 border-b text-left">Email</th>
        <th class="px-6 py-3 border-b text-left">City</th>
        <th class="px-6 py-3 border-b text-left">Joined</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($customers as $cust): ?>
        <tr class="hover:bg-gray-100">
          <td class="px-6 py-4 border-b"><?= htmlspecialchars($cust['id']) ?></td>
          <td class="px-6 py-4 border-b"><?= htmlspecialchars($cust['name']) ?></td> <!-- FIXED -->
          <td class="px-6 py-4 border-b"><?= htmlspecialchars($cust['email']) ?></td>
          <td class="px-6 py-4 border-b"><?= htmlspecialchars($cust['city']) ?></td>
          <td class="px-6 py-4 border-b"><?= htmlspecialchars($cust['created_at']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
