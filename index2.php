<?php
include 'db.php';
session_start();

if (!isset($_SESSION['business_id'])) {
    header("Location: Business_login.html");
    exit;
}

$businessId = $_SESSION['business_id'];

// Define categories and include an "All" option
$categories = ['Bridal Makeup', 'Hair Styling', 'Facial'];
$categoriesWithAll = array_merge(['All'], $categories);

// Get selected category from URL, default to 'All'
$currentCategory = $_GET['category'] ?? 'All';
if ($currentCategory !== 'All' && !in_array($currentCategory, $categories)) {
    $currentCategory = 'All';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>GlamConnect - Home</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://unpkg.com/alpinejs"></script>
</head>
<body class="bg-gray-50">

  <!-- Header -->
  <header class="bg-white shadow-md">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
      <h1 class="text-2xl font-bold text-black">GlamConnect</h1>
      <nav>
        <ul class="flex gap-6 text-gray-700">
          <li><a href="/gcf/backend/dashboard_business.php" class="hover:text-black">Dashboard</a></li>
          <li><a href="about.html" class="hover:text-black">About</a></li>
          <li><a href="Business_login.html" class="hover:text-black">Business Login</a></li>
          <li><a href="customer_login.html" class="hover:text-black">Customer Login</a></li>
        </ul>
      </nav>
    </div>
  </header>

<!-- Carousel -->
  <div class="relative mt-6 max-w-6xl mx-auto overflow-hidden rounded-lg shadow-lg" x-data="{ active: 0, slides: ['public/moshaz cero2.jpg', 'public/moshaz cero 3.jpg', 'public/moshaz cero4.jpg'] }" x-init="setInterval(() => active = (active + 1) % slides.length, 4000)">
    <template x-for="(slide, index) in slides" :key="index">
      <img :src="slide" x-show="active === index" class="w-full h-[480px] object-cover transition-opacity duration-700">
    </template>
  </div>
<!-- Add New Service -->
<div class="flex justify-center my-4">
  <a href="add_service.php" class="px-6 py-2 rounded-xl border bg-green-600 text-white font-semibold hover:bg-green-700 shadow-lg">
    + Add New Service
  </a>
</div>

<!-- Filter Buttons -->
<div class="flex justify-center space-x-4 my-6">
  <?php foreach ($categoriesWithAll as $cat): ?>
    <a href="?category=<?= urlencode($cat) ?>"
       class="px-4 py-2 rounded-xl border <?= $currentCategory === $cat ? 'bg-black text-white' : 'bg-gray-200 hover:bg-black hover:text-white' ?>">
      <?= htmlspecialchars($cat) ?>
    </a>
  <?php endforeach; ?>
</div>

<h1 class="text-4xl font-semibold text-center mb-5"><?= htmlspecialchars($currentCategory) ?></h1>

<?php
// Prepare and execute SQL based on selected category and business ID using PDO
if ($currentCategory === 'All') {
    $stmt = $conn->prepare("SELECT * FROM services WHERE business_id = ?");
    $stmt->execute([$businessId]);
} else {
   $stmt = $conn->prepare("SELECT * FROM services WHERE business_id = ? AND LOWER(TRIM(category)) = LOWER(TRIM(?))");

    $stmt->execute([$businessId, $currentCategory]);
}

$services = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($services) > 0):
?>
  <div class="flex flex-wrap justify-center">
    <?php foreach ($services as $row): ?>
      <div class="card mt-7 ml-6 mb-5 hover:shadow-2xl" style="width: 18rem;">
        <img src="<?= htmlspecialchars($row['image']) ?>" class="card-img-top w-80 h-56" alt="<?= htmlspecialchars($row['title']) ?>" />
        <div class="card-body">
          <h5 class="card-title font-bold"><?= htmlspecialchars($row['title']) ?></h5>
          <p class="text-gray-400"><?= htmlspecialchars($row['description']) ?></p>
        </div>
        <p class="list-group-itm font-semibold ml-4">Price: <?= htmlspecialchars($row['price']) ?> RS</p>
        <div class="card-body flex space-x-2">
          <a href="edit_service.php?id=<?= urlencode($row['id']) ?>"
             class="border-2 p-2 font-light rounded-2xl hover:font-normal hover:border-blue-600 text-blue-600">Edit</a>
          <a href="delete_service.php?id=<?= urlencode($row['id']) ?>"
             class="border-2 p-2 font-light rounded-2xl hover:font-normal hover:border-red-600 text-red-600"
             onclick="return confirm('Are you sure you want to delete this service?');">Delete</a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php else: ?>
  <p class="text-center text-gray-500">No services available.</p>
<?php endif; ?>

<!-- Footer -->

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
