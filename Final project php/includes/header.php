<?php
require_once 'config.php';

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit();
}

if ($_POST && isset($_POST['login_submit'])) {
    $email = clean($_POST['login_email']);
    $pass = $_POST['login_password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $error = "Invalid login credentials";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'PHP Project'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">MyWebsite</a>

            <div class="navbar-nav me-auto">
                <a class="nav-link" href="index.php">Home</a>
                <a class="nav-link" href="about.php">About</a>
                <a class="nav-link" href="register.php">Register</a>
                <?php if (checkLogin()): ?>
                    <a class="nav-link" href="users.php">Users</a>
                <?php endif; ?>
            </div>

            <div class="d-flex">
                <?php if (!checkLogin()): ?>
                    <form method="post" class="d-flex">
                        <input type="email" name="login_email" class="form-control form-control-sm me-2" placeholder="Email" required>
                        <input type="password" name="login_password" class="form-control form-control-sm me-2" placeholder="Password" required>
                        <button type="submit" name="login_submit" class="btn btn-sm btn-primary">Login</button>
                    </form>
                <?php else: ?>
                    <span class="text-light me-3">Hello <?php echo $_SESSION['username']; ?></span>
                    <a href="?logout=1" class="btn btn-sm btn-outline-light">Logout</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>