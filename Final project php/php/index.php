<?php
$title = "Home";
require_once 'config.php';

$stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
$posts = $stmt->fetchAll();

include 'header.php';
?>

<header class="bg-primary text-white p-4 rounded mb-4">
    <h1>Welcome to MyWebsite</h1>
    <p>A simple content management system</p>
</header>

<main>
    <section>
        <h2>Latest Posts</h2>

        <?php foreach ($posts as $post): ?>
            <article class="card mb-3">
                <div class="card-body">
                    <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                    <p><?php echo htmlspecialchars($post['content']); ?></p>
                    <small class="text-muted">Posted on <?php echo date('Y-m-d', strtotime($post['created_at'])); ?></small>
                </div>
            </article>
        <?php endforeach; ?>
    </section>

    <aside class="mt-4">
        <div class="card">
            <div class="card-header">
                <h4>Site Info</h4>
            </div>
            <div class="card-body">
                <?php if (checkLogin()): ?>
                    <p>Welcome back! You can manage content and users.</p>
                <?php else: ?>
                    <p>Please register or login to access more features.</p>
                <?php endif; ?>
            </div>
        </div>
    </aside>
</main>

<?php include 'footer.php'; ?>
