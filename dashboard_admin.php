<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Dashboard - Glam Connect</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal">

  <div class="flex">
    <!-- Sidebar -->
    <div class="w-64 h-screen bg-white shadow-md">
      <div class="p-6">
        <h1 class="text-2xl font-bold text-gray-800">Admin Panel</h1>
        <p class="text-sm text-gray-500">Welcome, Admin!</p>
      </div>
      <nav class="mt-6">
        <a href="#" class="block py-2.5 px-6 text-gray-700 hover:bg-gray-200">Dashboard</a>
        <a href="#" class="block py-2.5 px-6 text-gray-700 hover:bg-gray-200">Manage Businesses</a>
        <a href="#" class="block py-2.5 px-6 text-gray-700 hover:bg-gray-200">Manage Customers</a>
        <a href="#" class="block py-2.5 px-6 text-gray-700 hover:bg-gray-200">Appointments</a>
        <a href="logout.php" class="block py-2.5 px-6 text-red-600 hover:bg-red-100">Logout</a>
      </nav>
    </div>

    <!-- Main Content -->
    <div class="flex-1 p-10">
      <h2 class="text-3xl font-semibold mb-4">Dashboard Overview</h2>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
          <h3 class="text-xl font-medium text-gray-800">Total Businesses</h3>
          <p class="text-3xl text-blue-500 mt-2">52</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
          <h3 class="text-xl font-medium text-gray-800">Total Customers</h3>
          <p class="text-3xl text-green-500 mt-2">230</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
          <h3 class="text-xl font-medium text-gray-800">Today's Appointments</h3>
          <p class="text-3xl text-purple-500 mt-2">17</p>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
