<?php
declare(strict_types=1);

function app_config(): array
{
    static $config = null;
    if ($config === null) {
        $path = dirname(__DIR__) . '/config.php';
        if (!is_file($path)) {
            throw new RuntimeException('Missing config.php. Copy config.example.php and fill in your database credentials.');
        }
        $config = require $path;
    }
    return $config;
}

function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $db = app_config()['db'];
        $dsn = sprintf(
            'pgsql:host=%s;port=%d;dbname=%s',
            $db['host'],
            (int) $db['port'],
            $db['name']
        );
        $pdo = new PDO($dsn, $db['user'], $db['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
    return $pdo;
}

function start_session(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start([
            'cookie_httponly' => true,
            'cookie_samesite' => 'Lax',
        ]);
    }
}

function current_user(): ?array
{
    start_session();
    if (empty($_SESSION['user_id'])) {
        return null;
    }
    static $user = null;
    static $loadedId = null;
    $sessionId = (int) $_SESSION['user_id'];
    if ($user === null || $loadedId !== $sessionId) {
        $stmt = db()->prepare('SELECT id, username, email, display_name, bio, avatar_path, role, theme, created_at FROM users WHERE id = :id');
        $stmt->execute(['id' => $sessionId]);
        $user = $stmt->fetch() ?: null;
        $loadedId = $sessionId;
        if (!$user) {
            unset($_SESSION['user_id']);
            $loadedId = null;
        }
    }
    return $user;
}

function user_has_role(array $user, string ...$roles): bool
{
    return in_array($user['role'] ?? 'user', $roles, true);
}

function is_admin(?array $user = null): bool
{
    $user = $user ?? current_user();
    return $user && user_has_role($user, 'admin');
}

function is_moderator(?array $user = null): bool
{
    $user = $user ?? current_user();
    return $user && user_has_role($user, 'admin', 'moderator');
}

function require_admin(): array
{
    $user = require_login();
    if (!is_admin($user)) {
        http_response_code(403);
        exit('Forbidden');
    }
    return $user;
}

function require_moderator(): array
{
    $user = require_login();
    if (!is_moderator($user)) {
        http_response_code(403);
        exit('Forbidden');
    }
    return $user;
}

function require_login(): array
{
    $user = current_user();
    if (!$user) {
        header('Location: /login.php');
        exit;
    }
    return $user;
}

function require_login_api(): array
{
    $user = current_user();
    if (!$user) {
        json_error('Authentication required.', 401);
    }
    return $user;
}

function is_post(): bool
{
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function json_response(array $data, int $status = 200): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function json_error(string $message, int $status = 400): never
{
    json_response(['ok' => false, 'error' => $message], $status);
}

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function time_ago(string $datetime): string
{
    $time = strtotime($datetime);
    if ($time === false) {
        return $datetime;
    }
    $diff = time() - $time;
    if ($diff < 60) {
        return 'just now';
    }
    if ($diff < 3600) {
        $m = (int) floor($diff / 60);
        return $m . 'm';
    }
    if ($diff < 86400) {
        $h = (int) floor($diff / 3600);
        return $h . 'h';
    }
    if ($diff < 604800) {
        $d = (int) floor($diff / 86400);
        return $d . 'd';
    }
    return date('M j, Y', $time);
}

function avatar_url(?array $user): string
{
    if (!$user || empty($user['avatar_path'])) {
        return '/assets/img/default-avatar.svg';
    }
    return '/' . ltrim($user['avatar_path'], '/');
}

function post_image_url(?string $path): ?string
{
    if (!$path) {
        return null;
    }
    return '/' . ltrim($path, '/');
}

function save_upload(array $file, string $subdir, array $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp']): string
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        throw new InvalidArgumentException('Upload failed.');
    }
    if (($file['size'] ?? 0) > app_config()['app']['upload_max_bytes']) {
        throw new InvalidArgumentException('File is too large (max 5 MB).');
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']) ?: '';
    if (!in_array($mime, $allowedTypes, true)) {
        throw new InvalidArgumentException('Unsupported image type.');
    }
    $ext = match ($mime) {
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
        default => 'bin',
    };
    $dir = dirname(__DIR__) . '/uploads/' . trim($subdir, '/');
    if (!is_dir($dir) && !mkdir($dir, 0755, true) && !is_dir($dir)) {
        throw new RuntimeException('Could not create upload directory.');
    }
    $filename = bin2hex(random_bytes(16)) . '.' . $ext;
    $dest = $dir . '/' . $filename;
    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        throw new RuntimeException('Could not save uploaded file.');
    }
    return 'uploads/' . trim($subdir, '/') . '/' . $filename;
}

function post_stats(int $postId, ?int $viewerId = null): array
{
    $pdo = db();
    $likes = (int) $pdo->query("SELECT COUNT(*) FROM likes WHERE post_id = {$postId}")->fetchColumn();
    $comments = (int) $pdo->query("SELECT COUNT(*) FROM comments WHERE post_id = {$postId}")->fetchColumn();
    $reposts = (int) $pdo->query("SELECT COUNT(*) FROM reposts WHERE post_id = {$postId}")->fetchColumn();

    $liked = false;
    $reposted = false;
    if ($viewerId) {
        $stmt = $pdo->prepare('SELECT 1 FROM likes WHERE post_id = :post_id AND user_id = :user_id');
        $stmt->execute(['post_id' => $postId, 'user_id' => $viewerId]);
        $liked = (bool) $stmt->fetchColumn();

        $stmt = $pdo->prepare('SELECT 1 FROM reposts WHERE post_id = :post_id AND user_id = :user_id');
        $stmt->execute(['post_id' => $postId, 'user_id' => $viewerId]);
        $reposted = (bool) $stmt->fetchColumn();
    }

    return compact('likes', 'comments', 'reposts', 'liked', 'reposted');
}

function fetch_posts(int $limit = 30, int $offset = 0, ?int $userId = null, ?int $viewerId = null): array
{
    $sql = '
        SELECT p.*, u.username, u.display_name, u.avatar_path, u.role
        FROM posts p
        JOIN users u ON u.id = p.user_id
    ';
    $params = [];
    if ($userId) {
        $sql .= ' WHERE p.user_id = :user_id';
        $params['user_id'] = $userId;
    }
    $sql .= ' ORDER BY p.created_at DESC LIMIT ' . (int) $limit . ' OFFSET ' . (int) $offset;

    $stmt = db()->prepare($sql);
    foreach ($params as $key => $value) {
        $stmt->bindValue(':' . $key, $value, PDO::PARAM_INT);
    }
    $stmt->execute();
    $posts = $stmt->fetchAll();

    foreach ($posts as &$post) {
        $stats = post_stats((int) $post['id'], $viewerId);
        $post = array_merge($post, $stats);
    }
    unset($post);

    return $posts;
}
