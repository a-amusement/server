<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

$user = current_user();
$error = '';

if (is_post()) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Email and password are required.';
    } else {
        $stmt = db()->prepare('SELECT id, password_hash FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();
        if (!$row || !password_verify($password, $row['password_hash'])) {
            $error = 'Invalid email or password.';
        } else {
            start_session();
            $_SESSION['user_id'] = (int) $row['id'];
            header('Location: /home');
            exit;
        }
    }
}

page_start('Log in', 'auth', $user);
app_header($user);
?>
<main class="auth-page">
    <div class="auth-card">
        <div class="brand" style="margin-bottom:1rem;">
            <?php include __DIR__ . '/assets/img/logo.svg'; ?>
        </div>
        <h1>Log in</h1>
        <?php if ($error): ?>
        <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>
        <form method="post" class="form-grid">
            <label>
                Email
                <input type="email" name="email" required autocomplete="email" value="<?= e($_POST['email'] ?? '') ?>">
            </label>
            <label>
                Password
                <input type="password" name="password" required autocomplete="current-password">
            </label>
            <button type="submit" class="btn btn-primary">Log in</button>
        </form>
        <p class="muted">No account? <a href="/register.php">Sign up</a></p>
    </div>
</main>
<?php page_end(); ?>
