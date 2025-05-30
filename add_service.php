<?php
include 'config.php';
session_start();

if (!isset($_SESSION['business_id'])) {
    header("Location: Business_login.html");
    exit;
}

$businessId = $_SESSION['business_id'];
$categories = ['Bridal Makeup', 'Hair Styling', 'Facial'];
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = trim($_POST['category']);
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price = (int)$_POST['price'];

    if (!in_array($category, $categories)) {
        $errors[] = "Invalid category selected.";
    }

    if ($price <= 0) {
        $errors[] = "Price must be a positive number.";
    }

    if (empty($title) || empty($description)) {
        $errors[] = "Title and Description cannot be empty.";
    }

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $imageType = mime_content_type($imageTmpName);

        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!in_array($imageType, $allowedTypes)) {
            $errors[] = "Only JPG, JPEG, PNG files are allowed.";
        } else {
            $ext = pathinfo($imageName, PATHINFO_EXTENSION);
            $uniqueName = uniqid('service_', true) . '.' . $ext;
            $imageFolder = 'uploads/' . $uniqueName;

            if (!is_dir('uploads')) {
                mkdir('uploads', 0755, true);
            }
        }
    } else {
        $errors[] = "Image upload failed or no file selected.";
    }

    if (empty($errors)) {
        if (move_uploaded_file($imageTmpName, $imageFolder)) {
            $stmt = $conn->prepare("INSERT INTO services (category, title, description, price, image, business_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssisi", $category, $title, $description, $price, $imageFolder, $businessId);

            if ($stmt->execute()) {
                header("Location: index2.php");
                exit;
            } else {
                $errors[] = "Database insert failed: " . $stmt->error;
            }
        } else {
            $errors[] = "Failed to move uploaded image.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Add Service - GlamConnect</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">

  <!-- Header -->
  <header class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
      <h1 class="text-2xl font-bold">GlamConnect</h1>
      <nav class="space-x-6">
        <a href="backend/dashboard_business.php" class="hover:text-black">Dashboard</a>
        <a href="about.html" class="hover:text-black">About</a>
        <!-- <a href="navigation.html" class="hover:text-black">Login</a>
        <a href="business_signup.html" class="hover:text-black">List your Business</a> -->
      </nav>
    </div>
  </header>

  <!-- Main Section -->
  <main class="max-w-2xl mx-auto p-6 mt-10 bg-white rounded shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-center">Add New Service</h2>

    <?php if (!empty($errors)): ?>
      <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
        <ul class="list-disc pl-5 space-y-1">
          <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="space-y-5">
      <div>
        <label class="block mb-1 font-medium">Category</label>
        <select name="category" required class="w-full border border-gray-300 rounded px-3 py-2">
          <option value="" disabled <?= !isset($category) ? 'selected' : '' ?>>Select category</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= htmlspecialchars($cat) ?>" <?= (isset($category) && $category === $cat) ? 'selected' : '' ?>>
              <?= htmlspecialchars($cat) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label class="block mb-1 font-medium">Title</label>
        <input type="text" name="title" value="<?= isset($title) ? htmlspecialchars($title) : '' ?>" required
               class="w-full border border-gray-300 rounded px-3 py-2" />
      </div>

      <div>
        <label class="block mb-1 font-medium">Description</label>
        <textarea name="description" rows="4" required
                  class="w-full border border-gray-300 rounded px-3 py-2"><?= isset($description) ? htmlspecialchars($description) : '' ?></textarea>
      </div>

      <div>
        <label class="block mb-1 font-medium">Price (₹)</label>
        <input type="number" name="price" min="1" value="<?= isset($price) ? htmlspecialchars($price) : '' ?>" required
               class="w-full border border-gray-300 rounded px-3 py-2" />
      </div>

      <div>
        <label class="block mb-1 font-medium">Image</label>
        <input type="file" name="image" accept=".jpg,.jpeg,.png" required
               class="w-full border border-gray-300 rounded px-3 py-2" />
      </div>

      <button type="submit"
              class="w-full bg-black text-white py-2 rounded hover:bg-gray-800 transition">
        Add Service
      </button>
    </form>
  </main>

  <!-- Footer -->
  <footer class="bg-black text-white mt-20 px-6 py-10">
    <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 text-sm">
      <div>
        <h4 class="font-bold mb-2">About GlamConnect</h4>
        <ul class="space-y-1">
          <li><a href="#" class="hover:underline">Careers</a></li>
          <li><a href="#" class="hover:underline">Career Support</a></li>
        </ul>
      </div>
      <div>
        <h4 class="font-bold mb-2">For Business</h4>
        <ul class="space-y-1">
          <li><a href="#" class="hover:underline">For Partners</a></li>
          <li><a href="#" class="hover:underline">Support</a></li>
          <li><a href="#" class="hover:underline">Status</a></li>
        </ul>
      </div>
      <div>
        <h4 class="font-bold mb-2">Social Media</h4>
        <ul class="space-y-1">
          <li><a href="#" class="hover:underline">Facebook</a></li>
          <li><a href="#" class="hover:underline">Instagram</a></li>
          <li><a href="#" class="hover:underline">Twitter</a></li>
        </ul>
      </div>
    </div>
  </footer>

</body>
</html>

