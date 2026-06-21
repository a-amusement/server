<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/bootstrap.php';

$user = require_login_api();

if (!is_post()) {
    json_error('Method not allowed', 405);
}

$input = json_decode(file_get_contents('php://input') ?: '{}', true) ?: [];
$postId = (int) ($input['post_id'] ?? 0);
if ($postId <= 0) {
    json_error('Invalid post.');
}

$pdo = db();
$exists = $pdo->prepare('SELECT 1 FROM posts WHERE id = :id');
$exists->execute(['id' => $postId]);
if (!$exists->fetchColumn()) {
    json_error('Post not found.', 404);
}

$check = $pdo->prepare('SELECT id FROM likes WHERE post_id = :post_id AND user_id = :user_id');
$check->execute(['post_id' => $postId, 'user_id' => $user['id']]);
$existing = $check->fetchColumn();

if ($existing) {
    $del = $pdo->prepare('DELETE FROM likes WHERE id = :id');
    $del->execute(['id' => $existing]);
    $liked = false;
} else {
    $ins = $pdo->prepare('INSERT INTO likes (post_id, user_id) VALUES (:post_id, :user_id)');
    $ins->execute(['post_id' => $postId, 'user_id' => $user['id']]);
    $liked = true;
}

$stats = post_stats($postId, (int) $user['id']);
json_response(['ok' => true, 'liked' => $liked, 'likes' => $stats['likes']]);
