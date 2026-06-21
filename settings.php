<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

$user = require_login();
$message = '';
$error = '';

if (is_post()) {
    $displayName = trim($_POST['display_name'] ?? '');
    $bio = trim($_POST['bio'] ?? '');
    $theme = $_POST['theme'] ?? 'system';

    if (!in_array($theme, ['light', 'dark', 'system'], true)) {
        $theme = 'system';
    }
    if ($displayName === '') {
        $displayName = $user['username'];
    }

    $avatarPath = $user['avatar_path'];
    if (!empty($_FILES['avatar']['name'])) {
        try {
            $avatarPath = save_upload($_FILES['avatar'], 'avatars');
        } catch (Throwable $e) {
            $error = $e->getMessage();
        }
    }

    if ($error === '') {
        $stmt = db()->prepare('
            UPDATE users
            SET display_name = :display_name, bio = :bio, avatar_path = :avatar_path, theme = :theme, updated_at = NOW()
            WHERE id = :id
        ');
        $stmt->execute([
            'display_name' => $displayName,
            'bio' => $bio,
            'avatar_path' => $avatarPath,
            'theme' => $theme,
            'id' => $user['id'],
        ]);
        $user = current_user();
        $message = 'Profile updated.';
    }
}

page_start('Settings', 'app', $user);
app_header($user);
?>
<main class="page-shell">
    <aside class="sidebar sidebar-left"></aside>
    <section class="feed-column">
        <div class="profile-card">
            <h1>Profile settings</h1>
            <?php if ($message): ?>
            <div class="alert alert-success"><?= e($message) ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
            <div class="alert alert-error"><?= e($error) ?></div>
            <?php endif; ?>
            <form method="post" enctype="multipart/form-data" class="form-grid">
                <label>
                    Display name
                    <input type="text" name="display_name" maxlength="64" value="<?= e($user['display_name']) ?>">
                </label>
                <label>
                    Bio
                    <textarea name="bio" rows="4" maxlength="500"><?= e($user['bio']) ?></textarea>
                </label>
                <label>
                    Avatar
                    <input type="file" name="avatar" accept="image/jpeg,image/png,image/gif,image/webp">
                </label>
                <div>
                    <span>Theme preference</span>
                    <div class="theme-picker">
                        <?php foreach (['light' => 'Light', 'dark' => 'Dark', 'system' => 'System'] as $value => $label): ?>
                        <label>
                            <input type="radio" name="theme" value="<?= e($value) ?>" <?= $user['theme'] === $value ? 'checked' : '' ?>>
                            <?= e($label) ?>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save changes</button>
            </form>
        </div>
    </section>
    <aside class="sidebar sidebar-right"></aside>
</main>
<?php page_end(); ?>
