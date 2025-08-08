<?php
require_once '../includes/db.php';
$message = '';
if (!isset($_SESSION['user_id'])) {
    header("Location: /php/login.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = trim($_POST['product_name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $imagePath = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $target = '../uploads/' . uniqid() . '.' . $ext;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $imagePath = basename($target);
        }
    }
    if ($product_name && $description && $price) {
        $stmt = $pdo->prepare("INSERT INTO products (user_id, product_name, description, price, image_path) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $product_name, $description, $price, $imagePath]);
        $message = "Product created!";
    } else {
        $message = "All fields required!";
    }
}
include '../includes/header.php';
?>
    <h2>Add Product</h2>
<?php if ($message) echo "<p>$message</p>"; ?>
    <form method="post" enctype="multipart/form-data" class="mb-3">
        <input type="text" name="product_name" placeholder="Product Name" required class="form-control mb-2">
        <textarea name="description" placeholder="Description" required class="form-control mb-2"></textarea>
        <input type="number" step="0.01" name="price" placeholder="Price" required class="form-control mb-2">
        <input type="file" name="image" accept="image/*" class="form-control mb-2">
        <button type="submit" class="btn btn-success">Add</button>
    </form>
<?php include '../includes/footer.php'; ?>

