<?php
// Connect to your DB
include 'db.php'; 

$stmt = $conn->prepare("SELECT id, salon, address, city FROM business_users ORDER BY created_at DESC");
$stmt->execute();
$salons = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>GlamConnect - Home</title>
  <script src="https://cdn.tailwindcss.com"></script>
  
</head>

<body class="bg-gray-50">
  <header class="shadow-xl bg-white">
    <div class="container mx-auto px-6 py-4 flex justify-between items-center">
      <h1 class="text-2xl font-bold">GlamConnect</h1>
      <nav>
        <ul class="flex gap-6">
          <li><a href="#" class="hover:underline">Services</a></li>
          <li><a href="about.html" class="hover:underline">About</a></li>
          <li><a href="Business_login.html" class="hover:underline">Business Login</a></li>
          <li><a href="customer_login.html" class="hover:underline">Customer Login</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <section class="px-10 mt-12">
    <h2 class="text-3xl font-semibold mb-6">Book Local Beauty and Wellness Services</h2>
    <p class="text-lg text-gray-700 mb-6">Explore top-rated salons registered with GlamConnect</p>
    <a href="#" class="inline-block bg-black text-white px-6 py-3 rounded-full hover:bg-gray-700">Explore Now</a>
  </section>

  <section class="px-10 mt-16">
    <h3 class="text-2xl font-semibold mb-4">Registered Salons</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <?php if ($salons): ?>
        <?php foreach ($salons as $salon): ?>
          <div class="bg-white border hover:shadow-xl rounded-xl p-4">
            <img src="public/default-salon.png" class="w-full h-48 object-cover rounded-md" alt="Salon Image">
            <h4 class="text-lg font-semibold mt-3"><?= htmlspecialchars($salon['salon']) ?></h4>
            <p class="text-sm text-gray-600"><?= htmlspecialchars($salon['address']) ?>, <?= htmlspecialchars($salon['city']) ?></p>
            <a href="services.php?salon_id=<?= $salon['id'] ?>" class="inline-block mt-3 px-4 py-2 bg-black text-white rounded-full hover:bg-gray-700">Book Now</a>
         

          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="text-gray-500 col-span-full">No salons registered yet.</p>
      <?php endif; ?>
    </div>
  </section>

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
