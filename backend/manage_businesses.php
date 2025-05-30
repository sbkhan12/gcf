<?php
include 'db.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Handle delete request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $deleteId = $_POST['delete_id'];
    $stmt = $conn->prepare("DELETE FROM business_users WHERE id = ?");
    $stmt->execute([$deleteId]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Handle rating update request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating_id'], $_POST['rating'])) {
    $ratingId = $_POST['rating_id'];
    $newRating = $_POST['rating'];
    $stmt = $conn->prepare("UPDATE business_users SET rating = ? WHERE id = ?");
    $stmt->execute([$newRating, $ratingId]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$stmt = $conn->query("SELECT id, salon, email, city, rating, created_at FROM business_users ORDER BY created_at DESC");
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
<body class="bg-gray-50">
  <!-- Header -->
  <header class="shadow-xl bg-white">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
      <h1 class="text-2xl font-bold">GlamConnect</h1>
      <nav>
        <ul class="flex gap-4 text-gray-700">
          <li><a href="dashboard_admin.php" class="hover:underline">Dashboard</a></li>
          <li><a href="about.html" class="hover:underline">About</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Main Content -->
  <main class="max-w-7xl mx-auto mt-10 px-4 sm:px-8">
    <h2 class="text-3xl font-bold mb-6 text-gray-800">Manage Businesses</h2>
    <div class="overflow-x-auto bg-white rounded-lg shadow">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">ID</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Salon</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Email</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">City</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Rating</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Joined</th>
            <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
          <?php foreach ($businesses as $biz): ?>
          <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 text-sm"><?= htmlspecialchars($biz['id']) ?></td>
            <td class="px-6 py-4 text-sm"><?= htmlspecialchars($biz['salon']) ?></td>
            <td class="px-6 py-4 text-sm"><?= htmlspecialchars($biz['email']) ?></td>
            <td class="px-6 py-4 text-sm"><?= htmlspecialchars($biz['city']) ?></td>
            <td class="px-6 py-4 text-sm">
              <form method="POST" class="flex gap-2 items-center">
                <input type="hidden" name="rating_id" value="<?= $biz['id'] ?>">
                <select name="rating" class="border rounded px-2 py-1 text-sm">
                  <option <?= $biz['rating'] == 'Top Rated' ? 'selected' : '' ?>>Top Rated</option>
                  <option <?= $biz['rating'] == 'Best' ? 'selected' : '' ?>>Best</option>
                  <option <?= $biz['rating'] == 'Low Rated' ? 'selected' : '' ?>>Low Rated</option>
                </select>
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-sm">Update</button>
              </form>
            </td>
            <td class="px-6 py-4 text-sm"><?= htmlspecialchars($biz['created_at']) ?></td>
            <td class="px-6 py-4 text-sm">
              <form method="POST" onsubmit="return confirm('Are you sure you want to delete this business?');">
                <input type="hidden" name="delete_id" value="<?= $biz['id'] ?>">
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">Delete</button>
              </form>
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
