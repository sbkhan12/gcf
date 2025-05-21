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
  <div class="max-w-5xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold mb-2"><?= htmlspecialchars($salon['salon']) ?></h1>
    <p class="text-gray-600 mb-6"><?= htmlspecialchars($salon['address']) ?>, <?= htmlspecialchars($salon['city']) ?></p>

    <h2 class="text-2xl font-semibold mb-4">Available Services</h2>

    <?php if ($services): ?>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
</body>
</html>
