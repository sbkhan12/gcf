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

    // Validate category
    if (!in_array($category, $categories)) {
        $errors[] = "Invalid category selected.";
    }

    // Validate price
    if ($price <= 0) {
        $errors[] = "Price must be a positive number.";
    }

    // Validate required fields
    if (empty($title) || empty($description)) {
        $errors[] = "Title and Description cannot be empty.";
    }

    // Handle image upload
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


<!-- HTML Form -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Add New Service</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-6">
  <div class="max-w-md mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-bold mb-4">Add New Service</h2>

    <?php if (!empty($errors)): ?>
      <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">
        <ul>
          <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="space-y-4">
      <label class="block">
        <span class="text-gray-700">Category</span>
        <select name="category" required class="mt-1 block w-full rounded border-gray-300">
          <option value="" disabled <?= !isset($category) ? 'selected' : '' ?>>Select category</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?= htmlspecialchars($cat) ?>" <?= (isset($category) && $category === $cat) ? 'selected' : '' ?>>
              <?= htmlspecialchars($cat) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </label>

      <label class="block">
        <span class="text-gray-700">Title</span>
        <input type="text" name="title" value="<?= isset($title) ? htmlspecialchars($title) : '' ?>" required
          class="mt-1 block w-full rounded border-gray-300" />
      </label>

      <label class="block">
        <span class="text-gray-700">Description</span>
        <textarea name="description" required class="mt-1 block w-full rounded border-gray-300" rows="4"><?= isset($description) ? htmlspecialchars($description) : '' ?></textarea>
      </label>

      <label class="block">
        <span class="text-gray-700">Price (RS)</span>
        <input type="number" name="price" min="1" value="<?= isset($price) ? htmlspecialchars($price) : '' ?>" required
          class="mt-1 block w-full rounded border-gray-300" />
      </label>

      <label class="block">
        <span class="text-gray-700">Image</span>
        <input type="file" name="image" accept=".jpg,.jpeg,.png" required
          class="mt-1 block w-full" />
      </label>

      <button type="submit" class="w-full bg-black text-white py-2 rounded hover:bg-gray-800 transition">
        Add Service
      </button>
    </form>
  </div>
</body>
</html>
