<?php
require_once '../includes/db.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: /index.php");
        exit;
    } else {
        $message = "Login failed.";
    }
}
include '../includes/header.php';
?>
    <h2>Login</h2>
<?php if ($message) echo "<p>$message</p>"; ?>
    <form method="post" class="mb-3">
        <input type="text" name="username" placeholder="Username" required class="form-control mb-2">
        <input type="password" name="password" placeholder="Password" required class="form-control mb-2">
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
<?php include '../includes/footer.php'; ?>

