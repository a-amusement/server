<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

$viewer = current_user();
$username = trim($_GET['u'] ?? '');
if ($username === '') {
    header('Location: /');
    exit;
}

$stmt = db()->prepare('SELECT id, username, email, display_name, bio, avatar_path, role, theme, created_at FROM users WHERE username = :username');
$stmt->execute(['username' => $username]);
$profile = $stmt->fetch();
if (!$profile) {
    http_response_code(404);
    page_start('Not found', 'app', $viewer);
    app_header($viewer);
    echo '<main class="page-shell"><div class="auth-card"><h1>User not found</h1></div></main>';
    page_end();
    exit;
}

$posts = fetch_posts(30, 0, (int) $profile['id'], $viewer ? (int) $viewer['id'] : null);
$isOwn = $viewer && (int) $viewer['id'] === (int) $profile['id'];

page_start($profile['display_name'] ?: $profile['username'], 'app', $viewer);
app_header($viewer);
?>
<main class="page-shell">
    <aside class="sidebar sidebar-left"></aside>
    <section class="feed-column">
        <div class="profile-card">
            <div class="profile-hero">
                <img src="<?= e(avatar_url($profile)) ?>" alt="" class="avatar">
                <div>
                    <h1><?= e($profile['display_name'] ?: $profile['username']) ?></h1>
                    <p class="muted">@<?= e($profile['username']) ?></p>
                    <?php if ($profile['role'] !== 'user'): ?>
                    <span class="role-badge <?= e($profile['role']) ?>"><?= e($profile['role']) ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <?php if ($profile['bio']): ?>
            <p><?= nl2br(e($profile['bio'])) ?></p>
            <?php endif; ?>
            <?php if ($isOwn): ?>
            <p><a href="/settings.php" class="btn btn-ghost btn-sm">Edit profile</a></p>
            <?php endif; ?>
        </div>

        <?php if (!$posts): ?>
        <div class="sidebar-card"><p class="muted">No posts yet.</p></div>
        <?php endif; ?>

        <?php foreach ($posts as $post): ?>
            <?php render_post_card($post, $viewer); ?>
        <?php endforeach; ?>
    </section>
    <aside class="sidebar sidebar-right"></aside>
</main>
<?php page_end(); ?>
