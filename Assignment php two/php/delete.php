<?php
require_once '../includes/db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: /php/login.php");
    exit;
}
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("DELETE FROM products WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
header("Location: /index.php");
exit;

