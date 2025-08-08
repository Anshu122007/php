<?php
require_once '../includes/db.php';
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($username && $email && $password) {
        $pwHash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        try {
            $stmt->execute([$username, $email, $pwHash]);
            $message = "Account created. You can login.";
        } catch (PDOException $e) {
            $message = "Error: " . $e->getMessage();
        }
    } else {
        $message = "All fields required.";
    }
}
include '../includes/header.php';
?>
    <h2>Register</h2>
<?php if ($message) echo "<p>$message</p>"; ?>
    <form method="post" class="mb-3">
        <input type="text" name="username" placeholder="Username" required class="form-control mb-2">
        <input type="email" name="email" placeholder="Email" required class="form-control mb-2">
        <input type="password" name="password" placeholder="Password" required class="form-control mb-2">
        <button type="submit" class="btn btn-success">Register</button>
    </form>
<?php include '../includes/footer.php';

