<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';

function page_start(string $title, string $bodyClass = '', ?array $user = null): void
{
    $user = $user ?? current_user();
    $theme = $user['theme'] ?? 'system';
    $appName = app_config()['app']['name'];
    ?>
<!DOCTYPE html>
<html lang="en" data-theme="<?= e($theme) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title) ?> · <?= e($appName) ?></title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <script src="/assets/js/theme.js" defer></script>
    <?php if ($bodyClass === 'app'): ?>
    <script src="/assets/js/app.js" defer></script>
    <?php endif; ?>
</head>
<body class="<?= e($bodyClass) ?>" data-logged-in="<?= $user ? '1' : '0' ?>">
<?php
}

function page_end(): void
{
    ?>
</body>
</html>
<?php
}

function app_header(?array $user): void
{
    ?>
<header class="topbar">
    <div class="topbar-inner">
        <a class="brand" href="/home" aria-label="a-amusement home">
            <?php include dirname(__DIR__) . '/assets/img/logo.svg'; ?>
        </a>
        <nav class="topbar-nav">
            <a href="/home" class="nav-link">Feed</a>
            <?php if ($user): ?>
            <a href="/profile.php?u=<?= e($user['username']) ?>" class="nav-link">Profile</a>
            <?php if (is_admin($user)): ?>
            <a href="/admin.php" class="nav-link">Admin</a>
            <?php endif; ?>
            <a href="/settings.php" class="nav-link">Settings</a>
            <button type="button" class="icon-btn theme-toggle" aria-label="Toggle theme" title="Toggle theme">
                <span class="theme-icon theme-icon-light" aria-hidden="true">☀</span>
                <span class="theme-icon theme-icon-dark" aria-hidden="true">☾</span>
            </button>
            <a href="/logout.php" class="btn btn-ghost">Log out</a>
            <?php else: ?>
            <a href="/login.php" class="btn btn-ghost">Log in</a>
            <a href="/register.php" class="btn btn-primary">Sign up</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<?php
}

function render_post_card(array $post, ?array $viewer): void
{
    $image = post_image_url($post['image_path'] ?? '');
    $name = $post['display_name'] ?: $post['username'];
    ?>
<article class="post-card" data-post-id="<?= (int) $post['id'] ?>">
    <header class="post-header">
        <a href="/profile.php?u=<?= e($post['username']) ?>" class="post-author">
            <img src="<?= e(avatar_url(['avatar_path' => $post['avatar_path'] ?? ''])) ?>" alt="" class="avatar">
            <span class="author-meta">
                <strong><?= e($name) ?></strong>
                <span class="handle">@<?= e($post['username']) ?></span>
            </span>
        </a>
        <time datetime="<?= e($post['created_at']) ?>"><?= e(time_ago($post['created_at'])) ?></time>
        <?php if ($viewer && is_moderator($viewer)): ?>
        <button type="button" class="btn btn-ghost btn-sm admin-delete-post" data-post-id="<?= (int) $post['id'] ?>" title="Delete post">Delete</button>
        <?php endif; ?>
    </header>
    <?php if (!empty($post['content'])): ?>
    <p class="post-content"><?= nl2br(e($post['content'])) ?></p>
    <?php endif; ?>
    <?php if ($image): ?>
    <figure class="post-media">
        <img src="<?= e($image) ?>" alt="Post image" loading="lazy">
    </figure>
    <?php endif; ?>
    <footer class="post-actions">
        <button type="button" class="action-btn comment-toggle" data-post-id="<?= (int) $post['id'] ?>">
            <span aria-hidden="true">💬</span>
            <span class="count"><?= (int) ($post['comments'] ?? 0) ?></span>
        </button>
        <button type="button" class="action-btn repost-btn<?= !empty($post['reposted']) ? ' active' : '' ?>" data-post-id="<?= (int) $post['id'] ?>" <?= !$viewer ? 'disabled title="Log in to repost"' : '' ?>>
            <span aria-hidden="true">↻</span>
            <span class="count"><?= (int) ($post['reposts'] ?? 0) ?></span>
        </button>
        <button type="button" class="action-btn like-btn<?= !empty($post['liked']) ? ' active' : '' ?>" data-post-id="<?= (int) $post['id'] ?>" <?= !$viewer ? 'disabled title="Log in to like"' : '' ?>>
            <span aria-hidden="true">♥</span>
            <span class="count"><?= (int) ($post['likes'] ?? 0) ?></span>
        </button>
    </footer>
    <section class="comments-panel hidden" data-post-id="<?= (int) $post['id'] ?>">
        <div class="comments-list"></div>
        <?php if ($viewer): ?>
        <form class="comment-form" data-post-id="<?= (int) $post['id'] ?>">
            <textarea name="content" rows="2" maxlength="500" placeholder="Add a comment..." required></textarea>
            <button type="submit" class="btn btn-primary btn-sm">Reply</button>
        </form>
        <?php else: ?>
        <p class="muted"><a href="/login.php">Log in</a> to comment.</p>
        <?php endif; ?>
    </section>
</article>
<?php
}
