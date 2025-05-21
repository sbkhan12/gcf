<?php
include 'db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

$stmt = $conn->query("SELECT id, salon, email, city, created_at FROM business_users ORDER BY created_at DESC");
$businesses = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Manage Businesses - Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
  <h1 class="text-3xl mb-6 font-bold">Manage Businesses</h1>
  <table class="min-w-full bg-white rounded shadow">
    <thead>
      <tr>
        <th class="px-6 py-3 border-b text-left">ID</th>
        <th class="px-6 py-3 border-b text-left">Salon</th>
        <th class="px-6 py-3 border-b text-left">Email</th>
        <th class="px-6 py-3 border-b text-left">City</th>
        <th class="px-6 py-3 border-b text-left">Joined</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($businesses as $biz): ?>
        <tr class="hover:bg-gray-100">
          <td class="px-6 py-4 border-b"><?= htmlspecialchars($biz['id']) ?></td>
          <td class="px-6 py-4 border-b"><?= htmlspecialchars($biz['salon']) ?></td>
          <td class="px-6 py-4 border-b"><?= htmlspecialchars($biz['email']) ?></td>
          <td class="px-6 py-4 border-b"><?= htmlspecialchars($biz['city']) ?></td>
          <td class="px-6 py-4 border-b"><?= htmlspecialchars($biz['created_at']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
