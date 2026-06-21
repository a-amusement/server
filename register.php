<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/layout.php';

$user = current_user();
$error = '';

if (is_post()) {
    $username = strtolower(trim($_POST['username'] ?? ''));
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $displayName = trim($_POST['display_name'] ?? '');

    if (!preg_match('/^[a-z0-9_]{3,32}$/', $username)) {
        $error = 'Username must be 3–32 characters: letters, numbers, underscore.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Enter a valid email address.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters.';
    } else {
        try {
            $role = $username === 'admin' ? 'admin' : 'user';
            $stmt = db()->prepare('
                INSERT INTO users (username, email, password_hash, display_name, role)
                VALUES (:username, :email, :password_hash, :display_name, :role)
                RETURNING id
            ');
            $stmt->execute([
                'username' => $username,
                'email' => $email,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
                'display_name' => $displayName !== '' ? $displayName : $username,
                'role' => $role,
            ]);
            start_session();
            $_SESSION['user_id'] = (int) $stmt->fetchColumn();
            header('Location: /home');
            exit;
        } catch (PDOException $e) {
            if (str_contains($e->getMessage(), 'unique')) {
                $error = 'Username or email is already taken.';
            } else {
                $error = 'Could not create account. Please try again.';
            }
        }
    }
}

page_start('Sign up', 'auth', $user);
app_header($user);
?>
<main class="auth-page">
    <div class="auth-card">
        <div class="brand" style="margin-bottom:1rem;">
            <?php include __DIR__ . '/assets/img/logo.svg'; ?>
        </div>
        <h1>Create your account</h1>
        <?php if ($error): ?>
        <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif; ?>
        <form method="post" class="form-grid">
            <label>
                Username
                <input type="text" name="username" required pattern="[a-zA-Z0-9_]{3,32}" autocomplete="username" value="<?= e($_POST['username'] ?? '') ?>">
            </label>
            <label>
                Display name
                <input type="text" name="display_name" maxlength="64" autocomplete="name" value="<?= e($_POST['display_name'] ?? '') ?>">
            </label>
            <label>
                Email
                <input type="email" name="email" required autocomplete="email" value="<?= e($_POST['email'] ?? '') ?>">
            </label>
            <label>
                Password
                <input type="password" name="password" required minlength="8" autocomplete="new-password">
            </label>
            <button type="submit" class="btn btn-primary">Sign up</button>
        </form>
        <p class="muted">Already have an account? <a href="/login.php">Log in</a></p>
    </div>
</main>
<?php page_end(); ?>
