<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Moshaz Salon</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="homepagestyle.css" />
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
    crossorigin="anonymous"
  />
  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"
  ></script>
</head>

<body class="bg-gray-50">
  <!-- Navbar -->
  <div
    class="navbar shadow-xl px-6 py-4 flex justify-between items-center bg-white"
  >
    <div class="logo text-xl font-bold">GlamConnect</div>
    <nav>
      <ul class="flex space-x-6">
        <li class="hover:underline"><a href="#">Services</a></li>
        <li class="hover:underline"><a href="Business_login.html">Business Login</a></li>
        <li class="hover:underline"><a href="index.html">Customer Login</a></li>
      </ul>
    </nav>
  </div>

  <!-- Carousel -->
  <div
    id="carouselExampleCaptions"
    class="carousel slide mx-auto mt-4"
    style="width: 90%; max-width: 1300px"
  >
    <div class="carousel-indicators">
      <button
        type="button"
        data-bs-target="#carouselExampleCaptions"
        data-bs-slide-to="0"
        class="active"
        aria-current="true"
        aria-label="Slide 1"
      ></button>
      <button
        type="button"
        data-bs-target="#carouselExampleCaptions"
        data-bs-slide-to="1"
        aria-label="Slide 2"
      ></button>
      <button
        type="button"
        data-bs-target="#carouselExampleCaptions"
        data-bs-slide-to="2"
        aria-label="Slide 3"
      ></button>
    </div>
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img
          src="public/moshaz cero2.jpg"
          class="d-block w-100"
          style="height:480px"
          alt="..."
        />
      </div>
      <div class="carousel-item">
        <img
          src="public/moshaz cero 3.jpg"
          class="d-block w-100"
          style="height:480px"
          alt="..."
        />
      </div>
      <div class="carousel-item">
        <img
          src="public/moshaz cero4.jpg"
          class="d-block w-100"
          style="height:480px"
          alt="..."
        />
      </div>
    </div>
    <button
      class="carousel-control-prev"
      type="button"
      data-bs-target="#carouselExampleCaptions"
      data-bs-slide="prev"
    >
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button
      class="carousel-control-next"
      type="button"
      data-bs-target="#carouselExampleCaptions"
      data-bs-slide="next"
    >
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>

  <?php
  // Define categories and include an "All" option
  $categories = ['Bridal Makeup', 'Hair Styling', 'Facial'];
  $categoriesWithAll = array_merge(['All'], $categories);

  // Get selected category from URL, default to 'All'
  $currentCategory = $_GET['category'] ?? 'All';

  // Validate category value
  if ($currentCategory !== 'All' && !in_array($currentCategory, $categories)) {
      $currentCategory = 'All';
  }
  ?>

  <!-- Category Filter Buttons -->
  <div class="flex justify-center space-x-4 my-6">
    <?php foreach ($categoriesWithAll as $cat): ?>
      <a
        href="?category=<?= urlencode($cat) ?>"
        class="px-4 py-2 rounded-xl border <?= $currentCategory === $cat ? 'bg-black text-white' : 'bg-gray-200 hover:bg-black hover:text-white' ?>"
        ><?= htmlspecialchars($cat) ?></a
      >
    <?php endforeach; ?>
  </div>

  <h1 class="text-4xl font-semibold text-center mb-5">
    <?= htmlspecialchars($currentCategory) ?>
  </h1>

  <?php
  // Prepare and execute SQL based on category filter
  if ($currentCategory === 'All') {
      $stmt = $conn->prepare("SELECT * FROM services");
  } else {
      $stmt = $conn->prepare("SELECT * FROM services WHERE category = ?");
      $stmt->bind_param("s", $currentCategory);
  }
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0):
  ?>
    <div class="flex flex-wrap justify-center">
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="card mt-7 ml-6 mb-5 hover:shadow-2xl" style="width: 18rem;">
          <img
            src="<?= htmlspecialchars($row['image']) ?>"
            class="card-img-top w-80 h-56"
            alt="<?= htmlspecialchars($row['title']) ?>"
          />
          <div class="card-body">
            <h5 class="card-title font-bold">
              <?= htmlspecialchars($row['title']) ?>
            </h5>
            <p class="text-gray-400">
              <?= htmlspecialchars($row['description']) ?>
            </p>
          </div>
          <p class="list-group-itm font-semibold ml-4">
            Price: <?= htmlspecialchars($row['price']) ?> RS
          </p>
          <div class="card-body">
            <button
              class="border-2 p-2 font-light rounded-2xl hover:font-normal hover:border-black"
            >
              Book Now
            </button>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <p class="text-center text-gray-500">No services available.</p>
  <?php endif; ?>

  <!-- Footer -->
  <div class="footer bg-gray-100 mt-10 py-8">
    <div class="container mx-auto px-4">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
        <div>
          <h3 class="font-bold text-lg mb-2">About GlamConnect</h3>
          <ul>
            <li class="hover:underline"><a href="#">Careers</a></li>
            <li class="hover:underline"><a href="#">Careers Support</a></li>
          </ul>
        </div>
        <div>
          <h3 class="font-bold text-lg mb-2">For Business</h3>
          <ul>
            <li class="hover:underline"><a href="#">For Partner</a></li>
            <li class="hover:underline"><a href="#">Support</a></li>
            <li class="hover:underline"><a href="#">Status</a></li>
          </ul>
        </div>
        <div>
          <h3 class="font-bold text-lg mb-2">Social Media</h3>
          <ul>
            <li class="hover:underline"><a href="#">Facebook</a></li>
            <li class="hover:underline"><a href="#">Instagram</a></li>
            <li class="hover:underline"><a href="#">Twitter</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
