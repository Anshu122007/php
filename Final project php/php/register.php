<?php
$title = "Register";
require_once 'config.php';

if ($_POST && isset($_POST['register'])) {
    $user = clean($_POST['username']);
    $email = clean($_POST['email']);
    $pass1 = $_POST['password'];
    $pass2 = $_POST['confirm_password'];

    $errors = [];

    if (strlen($user) < 3) {
        $errors[] = "Username too short";
    }

    if (!isValidEmail($email)) {
        $errors[] = "Invalid email";
    }

    if (strlen($pass1) < 6) {
        $errors[] = "Password too short";
    }

    if ($pass1 !== $pass2) {
        $errors[] = "Passwords don't match";
    }

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
    $stmt->execute([$email, $user]);
    if ($stmt->fetch()) {
        $errors[] = "User already exists";
    }

    $image_name = 'default.jpg';
    if ($_FILES['image']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $file_ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (in_array($file_ext, $allowed)) {
            $image_name = time() . '.' . $file_ext;
            if (!is_dir('uploads')) mkdir('uploads', 0777, true);
            move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $image_name);
        } else {
            $errors[] = "Invalid file type";
        }
    }

    if (empty($errors)) {
        $hashed = password_hash($pass1, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, image) VALUES (?, ?, ?, ?)");

        if ($stmt->execute([$user, $email, $hashed, $image_name])) {
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['username'] = $user;
            header('Location: index.php');
            exit();
        } else {
            $errors[] = "Registration failed";
        }
    }
}

include 'header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h2>Create Account</h2>
            </div>
            <div class="card-body">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <div><?php echo $error; ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" value="<?php echo isset($_POST['username']) ? $_POST['username'] : ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo isset($_POST['email']) ? $_POST['email'] : ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Profile Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>

                    <button type="submit" name="register" class="btn btn-primary w-100">Register</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
