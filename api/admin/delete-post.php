<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/bootstrap.php';

$user = require_login_api();
if (!is_moderator($user)) {
    json_error('Forbidden.', 403);
}

if (!is_post()) {
    json_error('Method not allowed', 405);
}

$input = json_decode(file_get_contents('php://input') ?: '{}', true) ?: [];
$postId = (int) ($input['post_id'] ?? 0);
if ($postId <= 0) {
    json_error('Invalid post.');
}

$stmt = db()->prepare('DELETE FROM posts WHERE id = :id');
$stmt->execute(['id' => $postId]);
if ($stmt->rowCount() === 0) {
    json_error('Post not found.', 404);
}

json_response(['ok' => true]);
