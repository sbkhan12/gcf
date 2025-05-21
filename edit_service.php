<?php
include 'config.php';

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM services WHERE id=$id");
$service = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = $_POST['category'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_POST['image'];

    $stmt = $conn->prepare("UPDATE services SET category=?, title=?, description=?, price=?, image=? WHERE id=?");
    $stmt->bind_param("sssisi", $category, $title, $description, $price, $image, $id);
    $stmt->execute();

    header("Location: index2.php");
}
?>

<form method="POST">
    <input name="category" value="<?= $service['category'] ?>"><br>
    <input name="title" value="<?= $service['title'] ?>"><br>
    <textarea name="description"><?= $service['description'] ?></textarea><br>
    <input name="price" type="number" value="<?= $service['price'] ?>"><br>
    <input name="image" value="<?= $service['image'] ?>"><br>
    <button type="submit">Update Service</button>
</form>
