<?php
$title = "Users";
require_once 'config.php';
requireLogin();

if ($_POST && isset($_POST['update_user'])) {
    $user_id = $_POST['user_id'];
    $new_username = clean($_POST['username']);
    $new_email = clean($_POST['email']);

    if ($new_username && $new_email) {
        $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ? WHERE id = ?");
        $stmt->execute([$new_username, $new_email, $user_id]);
        $message = "User updated";
    }
}

if ($_POST && isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];

    if ($user_id != $_SESSION['user_id']) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $message = "User deleted";
    } else {
        $error = "Cannot delete yourself";
    }
}

$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();

include 'header.php';
?>

<h1>User Management</h1>

<?php if (isset($message)): ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3>All Users</h3>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
            <tr>
                <th>Image</th>
                <th>Username</th>
                <th>Email</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td>
                        <img src="uploads/<?php echo $user['image']; ?>" width="40" height="40" class="rounded" onerror="this.src='https://via.placeholder.com/40'">
                    </td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo date('Y-m-d', strtotime($user['created_at'])); ?></td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editUser(<?php echo $user['id']; ?>, '<?php echo addslashes($user['username']); ?>', '<?php echo addslashes($user['email']); ?>')">Edit</button>

                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                            <form style="display:inline" method="post" onsubmit="return confirm('Delete user?')">
                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                <button type="submit" name="delete_user" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post">
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="edit_user_id">
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" name="username" id="edit_user_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" id="edit_user_email" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="update_user" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function editUser(id, username, email) {
        document.getElementById('edit_user_id').value = id;
        document.getElementById('edit_user_name').value = username;
        document.getElementById('edit_user_email').value = email;
        new bootstrap.Modal(document.getElementById('editUserModal')).show();
    }
</script>

<?php include 'footer.php'; ?>
