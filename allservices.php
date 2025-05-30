<?php
include 'db.php';

// Fetch all services with business (salon) details
$stmt = $conn->prepare("
    SELECT 
        s.id, s.category, s.title, s.description, s.price, s.image, s.business_id,
        b.salon, b.city 
    FROM services s 
    JOIN business_users b ON s.business_id = b.id
");
$stmt->execute();
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-3xl font-bold mb-6 text-center">All Services</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($services as $service): ?>
                <div class="bg-white rounded shadow p-4">
                    <?php if (!empty($service['image'])): ?>
                        <img src="<?= htmlspecialchars($service['image']) ?>" alt="Service Image" class="w-full h-40 object-cover rounded mb-3">
                    <?php endif; ?>

                    <h2 class="text-xl font-semibold"><?= htmlspecialchars($service['title']) ?></h2>
                    <p class="text-sm text-gray-500"><?= htmlspecialchars($service['category']) ?> | <?= htmlspecialchars($service['salon']) ?>, <?= htmlspecialchars($service['city']) ?></p>
                    <p class="mt-2"><?= htmlspecialchars($service['description']) ?></p>
                    <p class="mt-2 font-semibold">Price: ₹<?= htmlspecialchars($service['price']) ?></p>

                    <a href="book_service.php?service_id=<?= $service['id'] ?>&salon_id=<?= $service['business_id'] ?>" 
                       class="inline-block mt-4 bg-black text-white px-4 py-2 rounded hover:bg-gray-800">
                        Book Now
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
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
