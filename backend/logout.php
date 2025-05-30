<?php
session_start();

// Clear session
$_SESSION = [];
session_unset();
session_destroy();


if (isset($_SESSION['admin_id'])) {
    header("Location: adminlogin.html");
} else {
    header("Location: ../index.php");
}
exit();
