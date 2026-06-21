<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

$user = require_login();

try {
    $posts = fetch_posts(30, 0, null, (int) $user['id']);
} catch (Throwable $e) {
    $posts = [];
    $feedError = 'Could not load the feed. Please try again shortly.';
}

page_start('Home', 'app', $user);
app_header($user);
?>
<main class="page-shell">
    <aside class="sidebar sidebar-left">
        <div class="sidebar-card">
            <h2>Welcome back</h2>
            <p class="muted">@<?= e($user['username']) ?></p>
            <p>Share a photo, a thought, or both with the feed.</p>
        </div>
    </aside>

    <section class="feed-column">
        <?php if (!empty($feedError)): ?>
        <div class="alert alert-error"><?= e($feedError) ?></div>
        <?php endif; ?>
        <div class="compose-card">
            <form id="compose-form" enctype="multipart/form-data">
                <textarea name="content" rows="3" maxlength="2000" placeholder="What's happening?"></textarea>
                <div id="compose-preview" class="preview-wrap hidden"></div>
                <div class="compose-actions">
                    <label class="file-label">
                        <input type="file" id="post-image" name="image" accept="image/jpeg,image/png,image/gif,image/webp">
                        <span>Add photo</span>
                    </label>
                    <button type="submit" class="btn btn-primary">Post</button>
                </div>
            </form>
        </div>

        <?php if (!$posts): ?>
        <div class="sidebar-card">
            <p class="muted">No posts yet. Be the first to share something.</p>
        </div>
        <?php endif; ?>

        <?php foreach ($posts as $post): ?>
            <?php render_post_card($post, $user); ?>
        <?php endforeach; ?>
    </section>

    <aside class="sidebar sidebar-right">
        <div class="sidebar-card">
            <h3>Tips</h3>
            <ul class="muted">
                <li>Posts support text, photos, or both.</li>
                <li>Tap comments to join the conversation.</li>
                <li>Use the moon/sun button for theme.</li>
            </ul>
        </div>
    </aside>
</main>
<?php page_end(); ?>
