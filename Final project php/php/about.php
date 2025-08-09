<?php
$title = "About";
require_once 'config.php';

if ($_POST && isset($_POST['update_post']) && checkLogin()) {
    $post_id = $_POST['post_id'];
    $new_title = clean($_POST['title']);
    $new_content = clean($_POST['content']);

    if ($new_title && $new_content) {
        $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
        $stmt->execute([$new_title, $new_content, $post_id]);
        $message = "Post updated successfully";
    }
}

if ($_POST && isset($_POST['delete_post']) && checkLogin()) {
    $post_id = $_POST['post_id'];
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$post_id]);
    $message = "Post deleted successfully";
}

$stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
$posts = $stmt->fetchAll();

include 'header.php';
?>

<?php if (isset($message)): ?>
    <div class="alert alert-success"><?php echo $message; ?></div>
<?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <h1>About Our Website</h1>
            <p>This is a content management system built for our PHP final project.</p>

            <h2>Website Content</h2>

            <?php foreach ($posts as $post): ?>
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between">
                        <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                        <?php if (checkLogin()): ?>
                            <div>
                                <button class="btn btn-sm btn-warning" onclick="editPost(<?php echo $post['id']; ?>, '<?php echo addslashes($post['title']); ?>', '<?php echo addslashes($post['content']); ?>')">Edit</button>
                                <form style="display:inline" method="post" onsubmit="return confirm('Delete this post?')">
                                    <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                    <button type="submit" name="delete_post" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <p><?php echo htmlspecialchars($post['content']); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h4>Project Features</h4>
                </div>
                <div class="card-body">
                    <ul>
                        <li>User registration</li>
                        <li>Login system</li>
                        <li>Content management</li>
                        <li>CRUD operations</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

<?php if (checkLogin()): ?>
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Edit Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="post">
                    <div class="modal-body">
                        <input type="hidden" name="post_id" id="edit_id">
                        <div class="mb-3">
                            <label>Title</label>
                            <input type="text" name="title" id="edit_title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Content</label>
                            <textarea name="content" id="edit_content" class="form-control" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_post" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function editPost(id, title, content) {
            document.getElementById('edit_id').value = id;
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_content').value = content;
            new bootstrap.Modal(document.getElementById('editModal')).show();
        }
    </script>
<?php endif; ?>

<?php include 'footer.php'; ?>