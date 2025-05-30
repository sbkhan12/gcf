<?php
include 'db.php';

// Fetch salons
$stmt = $conn->prepare("SELECT id, salon, address, city, rating_category, image FROM business_users ORDER BY created_at DESC");
$stmt->execute();
$salons = $stmt->fetchAll(PDO::FETCH_ASSOC);

$categorizedSalons = [
    'Top Rated' => [],
    'Recommended' => [],
];

foreach ($salons as $salon) {
    $cat = $salon['rating_category'] ?? 'Recommended';
    $cat = isset($categorizedSalons[$cat]) ? $cat : 'Recommended';
    $categorizedSalons[$cat][] = $salon;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Home - GlamConnect</title>
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

  <!-- Navbar (same as services.php) -->
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

  <!-- Hero Section -->
  <section class="max-w-7xl mx-auto px-6 py-16 text-center">
    <h2 class="text-4xl font-bold text-gray-800 mb-4">Find & Book Beauty Services Near You</h2>
    <p class="text-lg text-gray-700 mb-8 max-w-xl mx-auto">Browse salons listed with GlamConnect. Choose by rating and location. Book appointments in a few clicks.</p>
    <a href="allservices.php" class="inline-block bg-gradient-to-r from-purple-600 to-pink-500 text-white px-6 py-3 rounded-full hover:from-pink-600 hover:to-red-400 transition">Explore Services</a>
  </section>

  <!-- Salon Listings -->
  <?php foreach (['Top Rated', 'Recommended'] as $category): ?>
    <section class="max-w-7xl mx-auto px-6 py-12">
      <h3 class="text-3xl font-bold text-gray-800 mb-8"><?= htmlspecialchars($category) ?> Salons</h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        <?php if (!empty($categorizedSalons[$category])): ?>
          <?php foreach ($categorizedSalons[$category] as $salon): ?>
            <div class="bg-white rounded-xl shadow-lg p-5 transition hover:shadow-2xl animate-fade-in">
              <?php
                $imgSrc = !empty($salon['image']) ? htmlspecialchars($salon['image']) : 'public/default-salon.png';
              ?>
              <img src="<?= $imgSrc ?>" alt="<?= htmlspecialchars($salon['salon']) ?> Image" class="w-full h-48 object-cover rounded-md mb-4">
              <h4 class="text-xl font-semibold text-gray-800 mb-1"><?= htmlspecialchars($salon['salon']) ?></h4>
              <p class="text-sm text-gray-500 mb-2"><?= htmlspecialchars($salon['address']) ?>, <?= htmlspecialchars($salon['city']) ?></p>
              <a href="services.php?salon_id=<?= $salon['id'] ?>" class="inline-block mt-4 bg-gradient-to-r from-purple-600 to-pink-500 text-white px-4 py-2 rounded-full hover:from-pink-600 hover:to-red-400 transition">
                View Services
              </a>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-span-full bg-white rounded-lg p-6 text-center text-gray-600">
            No <?= strtolower($category) ?> salons found.
          </div>
        <?php endif; ?>
      </div>
    </section>
  <?php endforeach; ?>

  <!-- Footer (same as services.php) -->
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
