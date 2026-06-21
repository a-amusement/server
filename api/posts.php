<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/bootstrap.php';

$user = require_login_api();

if (!is_post()) {
    json_error('Method not allowed', 405);
}

$content = trim($_POST['content'] ?? '');
$hasImage = !empty($_FILES['image']['name']);

if ($content === '' && !$hasImage) {
    json_error('Add text or a photo.');
}

$imagePath = '';
if ($hasImage) {
    try {
        $imagePath = save_upload($_FILES['image'], 'posts');
    } catch (Throwable $e) {
        json_error($e->getMessage());
    }
}

$stmt = db()->prepare('INSERT INTO posts (user_id, content, image_path) VALUES (:user_id, :content, :image_path) RETURNING id');
$stmt->execute([
    'user_id' => $user['id'],
    'content' => $content,
    'image_path' => $imagePath,
]);
$postId = (int) $stmt->fetchColumn();

json_response(['ok' => true, 'post_id' => $postId]);
