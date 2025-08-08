<?php
require_once 'includes/db.php';
include 'includes/header.php';

$stmt = $pdo->query("SELECT products.*, users.username FROM products JOIN users ON products.user_id = users.id ORDER BY products.created_at DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1 class="mb-4">Products</h1>
<div class="row">
    <?php foreach ($products as $product): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <?php if (!empty($product['image_path'])): ?>
                    <img src="/uploads/<?= htmlspecialchars($product['image_path']) ?>" class="card-img-top" alt="Image">
                <?php else: ?>
                    <img src="/images/no-image.png" class="card-img-top" alt="No image available">
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($product['product_name']) ?></h5>
                    <p class="card-text"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
                    <p class="card-text"><strong>Price:</strong> $<?= number_format($product['price'], 2) ?></p>
                    <p class="card-text"><small class="text-muted">Added by: <?= htmlspecialchars($product['username']) ?></small></p>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>

