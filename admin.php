<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

$user = require_admin();
$message = '';
$error = '';

if (is_post()) {
    $action = $_POST['action'] ?? '';
    if ($action === 'set_role') {
        $targetId = (int) ($_POST['user_id'] ?? 0);
        $role = $_POST['role'] ?? 'user';
        if (!in_array($role, ['user', 'moderator', 'admin'], true)) {
            $error = 'Invalid role.';
        } elseif ($targetId === (int) $user['id'] && $role !== 'admin') {
            $error = 'You cannot remove your own admin role.';
        } else {
            $stmt = db()->prepare('UPDATE users SET role = :role, updated_at = NOW() WHERE id = :id');
            $stmt->execute(['role' => $role, 'id' => $targetId]);
            $message = 'Role updated.';
        }
    } elseif ($action === 'delete_post') {
        $postId = (int) ($_POST['post_id'] ?? 0);
        if ($postId > 0) {
            $stmt = db()->prepare('DELETE FROM posts WHERE id = :id');
            $stmt->execute(['id' => $postId]);
            $message = 'Post deleted.';
        }
    }
}

$users = db()->query('
    SELECT id, username, display_name, email, role, created_at
    FROM users
    ORDER BY created_at ASC
')->fetchAll();

$recentPosts = db()->query('
    SELECT p.id, p.content, p.created_at, u.username
    FROM posts p
    JOIN users u ON u.id = p.user_id
    ORDER BY p.created_at DESC
    LIMIT 20
')->fetchAll();

page_start('Admin', 'app', $user);
app_header($user);
?>
<main class="page-shell">
    <aside class="sidebar sidebar-left"></aside>
    <section class="feed-column">
        <div class="profile-card">
            <h1>Administration</h1>
            <p class="muted">Manage roles and moderate content.</p>
            <?php if ($message): ?>
            <div class="alert alert-success"><?= e($message) ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
            <?php endif; ?>
        </div>

        <div class="profile-card">
            <h2>Users</h2>
            <div class="admin-table-wrap">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $row): ?>
                        <tr>
                            <td>
                                <strong>@<?= e($row['username']) ?></strong>
                                <?php if ($row['display_name']): ?>
                                <span class="muted"> · <?= e($row['display_name']) ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?= e($row['email']) ?></td>
                            <td>
                                <form method="post" class="inline-form">
                                    <input type="hidden" name="action" value="set_role">
                                    <input type="hidden" name="user_id" value="<?= (int) $row['id'] ?>">
                                    <select name="role">
                                        <?php foreach (['user', 'moderator', 'admin'] as $role): ?>
                                        <option value="<?= e($role) ?>" <?= $row['role'] === $role ? 'selected' : '' ?>><?= e($role) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" class="btn btn-ghost btn-sm">Save</button>
                                </form>
                            </td>
                            <td><a href="/profile.php?u=<?= e($row['username']) ?>">View</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="profile-card">
            <h2>Recent posts</h2>
            <?php if (!$recentPosts): ?>
            <p class="muted">No posts yet.</p>
            <?php endif; ?>
            <?php foreach ($recentPosts as $post): ?>
            <div class="admin-post-row">
                <div>
                    <strong>@<?= e($post['username']) ?></strong>
                    <span class="muted"> · <?= e(time_ago($post['created_at'])) ?></span>
                    <p><?= e(strlen($post['content']) > 120 ? substr($post['content'], 0, 120) . '…' : $post['content']) ?></p>
                </div>
                <form method="post" onsubmit="return confirm('Delete this post?');">
                    <input type="hidden" name="action" value="delete_post">
                    <input type="hidden" name="post_id" value="<?= (int) $post['id'] ?>">
                    <button type="submit" class="btn btn-ghost btn-sm">Delete</button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <aside class="sidebar sidebar-right"></aside>
</main>
<?php page_end(); ?>
