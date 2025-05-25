<?php

include 'db.php';

$salon_id = isset($_GET['salon_id']) ? intval($_GET['salon_id']) : 0;

// Fetch salon info
$stmt = $conn->prepare("SELECT * FROM business_users WHERE id = ?");
$stmt->execute([$salon_id]);
$salon = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle missing salon
if (!$salon) {
    echo "<h2 class='text-center text-red-600 mt-10'>Salon not found.</h2>";
    exit;
}

// Fetch services
$serviceStmt = $conn->prepare("SELECT * FROM services WHERE business_id = ?");
$serviceStmt->execute([$salon_id]);
$services = $serviceStmt->fetchAll(PDO::FETCH_ASSOC);
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
  </header>
  <div class="max-w-5xl mx-auto px-4 py-10">
    <h1 class="text-4xl font-bold mb-2"><?= htmlspecialchars($salon['salon']) ?></h1>
    <p class="text-gray-600 mb-6"><?= htmlspecialchars($salon['address']) ?>, <?= htmlspecialchars($salon['city']) ?></p>
<br>
    <h2 class="text-3xl font-semibold mb-4">Services</h2>

    <?php if ($services): ?>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <?php foreach ($services as $service): ?>
          <div class="border bg-white rounded-xl p-5 shadow hover:shadow-lg transition">
            
            <!-- Service Image -->
            <?php if (!empty($service['image'])): ?>
              <img src="<?= htmlspecialchars($service['image']) ?>" alt="Service Image" class="w-full h-48 object-cover rounded mb-4">
            <?php endif; ?>

            <!-- Service Info -->
            <h3 class="text-xl font-semibold mb-1"><?= htmlspecialchars($service['title']) ?></h3>
            <p class="text-gray-700 mb-1"><strong>Description:</strong> <?= htmlspecialchars($service['description']) ?></p>
            <p class="text-gray-900 font-semibold mb-4"><strong>Price:</strong> Rs <?= htmlspecialchars($service['price']) ?></p>

            <!-- Booking Form -->
            <form method="POST" action="book_service.php" class="mt-2">
              <input type="hidden" name="service_id" value="<?= $service['id'] ?>">
              <input type="hidden" name="salon_id" value="<?= $salon_id ?>">
              <button type="submit" class="bg-black text-white px-4 py-2 rounded hover:bg-gray-700">Book Now</button>
            </form>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <p>No services listed yet.</p>
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
