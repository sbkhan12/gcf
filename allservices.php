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
  <meta charset="UTF-8" />
  <title>All Services - GlamConnect</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @keyframes fade-in {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
      animation: fade-in 0.5s ease-out;
    }
  </style>
</head>

<body class="bg-gradient-to-br from-pink-100 via-purple-100 to-indigo-100 min-h-screen">

  <!-- Navbar -->
  <header class="bg-gradient-to-r from-purple-600 via-pink-500 to-red-400 shadow-md text-white">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
      <h1 class="text-2xl font-bold">GlamConnect</h1>
      <nav class="hidden md:flex gap-6 font-medium">
        <a href="index.php" class="hover:text-yellow-300 transition duration-300">Home</a>
        <a href="about.html" class="hover:text-yellow-300 transition duration-300">About</a>
        <a href="navigation.html" class="hover:text-yellow-300 transition duration-300">Login</a>
        <a href="business_signup.html" class="hover:text-yellow-300 transition duration-300">List your Business</a>
      </nav>
    </div>
  </header>

  <!-- Services Section -->
  <section class="max-w-7xl mx-auto px-4 py-12">
    <h1 class="text-4xl font-bold text-center text-gray-800 mb-10">All Services</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
      <?php foreach ($services as $service): ?>
        <div class="bg-white rounded-xl shadow-lg p-5 transition hover:shadow-2xl animate-fade-in">
          <?php if (!empty($service['image'])): ?>
            <img src="<?= htmlspecialchars($service['image']) ?>" alt="Service Image" class="w-full h-48 object-cover rounded-md mb-4">
          <?php else: ?>
            <img src="public/default-service.png" alt="Default" class="w-full h-48 object-cover rounded-md mb-4">
          <?php endif; ?>

          <h2 class="text-xl font-semibold text-gray-800"><?= htmlspecialchars($service['title']) ?></h2>
          <p class="text-sm text-gray-500 mb-1"><?= htmlspecialchars($service['category']) ?> | <?= htmlspecialchars($service['salon']) ?>, <?= htmlspecialchars($service['city']) ?></p>
          <p class="text-gray-700 mb-2"><?= htmlspecialchars($service['description']) ?></p>
          <p class="font-semibold text-indigo-700">Price: ₹<?= htmlspecialchars($service['price']) ?></p>

          <a href="book_service.php?service_id=<?= $service['id'] ?>&salon_id=<?= $service['business_id'] ?>"
             class="inline-block mt-4 bg-gradient-to-r from-purple-600 to-pink-500 text-white px-4 py-2 rounded-full hover:from-pink-600 hover:to-red-400 transition">
            Book Now
          </a>
        </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Footer -->
  <footer class="bg-black text-white mt-20 py-12 px-6 md:px-10">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <div>
        <h4 class="font-bold mb-3">About GlamConnect</h4>
        <ul class="space-y-2">
          <li><a href="#" class="hover:underline">Careers</a></li>
          <li><a href="#" class="hover:underline">Career Support</a></li>
        </ul>
      </div>
      <div>
        <h4 class="font-bold mb-3">For Business</h4>
        <ul class="space-y-2">
          <li><a href="#" class="hover:underline">For Partners</a></li>
          <li><a href="#" class="hover:underline">Support</a></li>
          <li><a href="#" class="hover:underline">Status</a></li>
        </ul>
      </div>
      <div>
        <h4 class="font-bold mb-3">Social Media</h4>
        <ul class="space-y-2">
          <li><a href="#" class="hover:underline">Facebook</a></li>
          <li><a href="#" class="hover:underline">Instagram</a></li>
          <li><a href="#" class="hover:underline">Twitter</a></li>
        </ul>
      </div>
    </div>
    <div class="text-center text-sm mt-10 text-gray-400">
      &copy; <?= date('Y') ?> GlamConnect. All rights reserved.
    </div>
  </footer>

</body>
</html>
