<?php
// Start the session
session_start();

// Check if the user is logged in as an admin
if (!isset($_SESSION['admin_id'])) {
    // If the admin is not logged in, redirect to login page
    header("Location: admin_login.php");
    exit;
}

// Include the database connection

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Glam Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>

    <!-- Sidebar and Content Area -->
    <div class="flex">
        <!-- Sidebar -->
        <div class="w-1/5 bg-gray-800 text-white p-4">
            <h2 class="text-2xl font-semibold mb-6">Admin Panel</h2>
            <ul class="space-y-4">
                <li><a href="admin_dashboard.php" class="hover:text-gray-400">Dashboard</a></li>
                <li><a href="manage_users.php" class="hover:text-gray-400">Manage Users</a></li>
                <li><a href="manage_posts.php" class="hover:text-gray-400">Manage Posts</a></li>
                <li><a href="logout.php" class="hover:text-gray-400">Logout</a></li>
            </ul>
        </div>

        <!-- Main Content Area -->
        <div class="w-4/5 p-8">
            <h1 class="text-3xl font-semibold mb-4">Welcome, Admin</h1>
            <!-- <p class="text-gray-600 mb-6">Hello, <?php echo htmlspecialchars($admin['email']); ?>! You are logged in as an admin.</p> -->

            <!-- Stats/Overview (Optional) -->
            <div class="grid grid-cols-3 gap-6 mb-6">
                <div class="p-4 bg-blue-200 rounded-lg shadow">
                    <h3 class="text-xl font-medium">Total Users</h3>
                 
                </div>
                <div class="p-4 bg-green-200 rounded-lg shadow">
                    <h3 class="text-xl font-medium">Total Posts</h3>
                   
                </div>
                <div class="p-4 bg-yellow-200 rounded-lg shadow">
                    <h3 class="text-xl font-medium">Pending Approvals</h3>
                  
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="flex space-x-6">
                <a href="manage_users.php" class="p-4 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600">Manage Users</a>
                <a href="manage_posts.php" class="p-4 bg-green-500 text-white rounded-lg shadow hover:bg-green-600">Manage Posts</a>
            </div>
        </div>
    </div>

</body>

</html>

