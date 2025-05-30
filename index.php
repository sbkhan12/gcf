<?php
include 'db.php';

// Fetch salons including the image column
$stmt = $conn->prepare("SELECT id, salon, address, city, rating_category, image FROM business_users ORDER BY created_at DESC");
$stmt->execute();
$salons = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Organize salons by rating category
$categorizedSalons = [
    'Top Rated' => [],
    'Best' => [],
    'Low Rated' => [],
];

foreach ($salons as $salon) {
    $cat = $salon['rating_category'] ?? 'Low Rated';
    if (!isset($categorizedSalons[$cat])) {
        $cat = 'Low Rated';  // fallback if unexpected category
    }
    $categorizedSalons[$cat][] = $salon;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>GlamConnect - Home</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    function toggleMenu() {
      document.getElementById('mobileMenu').classList.toggle('hidden');
    }
  </script>
</head>

<body class="bg-gray-50 text-gray-800">

  <!-- Header -->
  <header class="bg-white shadow-md">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
      <h1 class="text-2xl font-bold text-black">GlamConnect</h1>
      <nav class="hidden md:flex gap-6">
        <a href="allservices.php" class="hover:underline">Services</a>
        <a href="about.html" class="hover:underline">About</a>
        <a href="navigation.html" class="hover:underline">Login</a>
        <a href="business_signup.html" class="hover:underline">List your Business</a>
      </nav>
      <button onclick="toggleMenu()" class="md:hidden text-gray-700 focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 6h16M4 12h16M4 18h16" />
        </svg>
      </button>
    </div>
    <div id="mobileMenu" class="hidden px-6 pb-4 md:hidden">
      <a href="allservices.php" class="block py-2">Services</a>
      <a href="about.html" class="block py-2">About</a>
      <a href="navigation.html" class="block py-2">Login</a>
      <a href="business_signup.html" class="block py-2">List your Business</a>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="px-6 md:px-10 py-16 text-center md:text-left">
    <h2 class="text-4xl md:text-5xl font-bold mb-4">Book Local Beauty and Wellness Services</h2>
    <p class="text-lg text-gray-700 mb-6 max-w-xl">Explore top-rated salons registered with GlamConnect and book appointments with ease.</p>
    <a href="allservices.php" class="inline-block bg-black text-white px-6 py-3 rounded-full hover:bg-gray-800 transition">Explore Now</a>
  </section>

  <!-- Categorized Salons -->
  <?php foreach (['Top Rated', 'Best', 'Low Rated'] as $category): ?>
  <section class="px-6 md:px-10 py-10">
    <h3 class="text-2xl font-semibold mb-6"><?= htmlspecialchars($category) ?> Salons</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <?php if (!empty($categorizedSalons[$category])): ?>
        <?php foreach ($categorizedSalons[$category] as $salon): ?>
          <div class="bg-white border rounded-xl shadow hover:shadow-xl transition p-4">
            <?php
              // Use uploaded image if exists, else fallback to default
              $imgSrc = !empty($salon['image']) ? htmlspecialchars($salon['image']) : 'public/default-salon.png';
            ?>
            <img src="<?= $imgSrc ?>" class="w-full h-48 object-cover rounded-md" alt="Salon Image">
            <h4 class="text-lg font-semibold mt-3"><?= htmlspecialchars($salon['salon']) ?></h4>
            <p class="text-sm text-gray-600"><?= htmlspecialchars($salon['address']) ?>, <?= htmlspecialchars($salon['city']) ?></p>
            <a href="services.php?salon_id=<?= $salon['id'] ?>" class="inline-block mt-3 px-4 py-2 bg-black text-white rounded-full hover:bg-gray-700 transition">Book Now</a>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-span-full bg-white p-6 rounded-lg shadow text-center">
          <img src="public/default-salon.png" class="mx-auto mb-4 w-24 h-24 rounded-full object-cover" alt="No Salon">
          <p class="text-gray-600 text-lg">No <?= strtolower($category) ?> salons found.</p>
        </div>
      <?php endif; ?>
    </div>
  </section>
  <?php endforeach; ?>

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
        <h4 class="font-bold mb-3">Follow Us</h4>
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
