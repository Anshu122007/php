<?php
require_once '../includes/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: /php/login.php");
    exit;
}
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$product) { echo "Not found or no permission."; exit; }
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = trim($_POST['product_name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $imagePath = $product['image_path'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $target = '../uploads/' . uniqid() . '.' . $ext;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $imagePath = basename($target);
        }
    }
    if ($product_name && $description && $price) {
        $stmt = $pdo->prepare("UPDATE products SET product_name=?, description=?, price=?, image_path=? WHERE id=? AND user_id=?");
        $stmt->execute([$product_name, $description, $price, $imagePath, $id, $_SESSION['user_id']]);
        $message = "Product updated!";
    } else {
        $message = "All fields required!";
    }
}
include '../includes/header.php';
?>
    <h2>Edit Product</h2>
<?php if ($message) echo "<p>$message</p>"; ?>
    <form method="post" enctype="multipart/form-data" class="mb-3">
        <input type="text" name="product_name" value="<?= htmlspecialchars($product['product_name']) ?>" required class="form-control mb-2">
        <textarea name="description" required class="form-control mb-2"><?= htmlspecialchars($product['description']) ?></textarea>
        <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($product['price']) ?>" required class="form-control mb-2">
        <input type="file" name="image" accept="image/*" class="form-control mb-2">
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
<?php include '../includes/footer.php'; ?>

