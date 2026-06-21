<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/bootstrap.php';

$postId = (int) ($_GET['post_id'] ?? 0);
if ($postId <= 0) {
    json_error('Invalid post.');
}

if (is_post()) {
    $user = require_login_api();
    $input = json_decode(file_get_contents('php://input') ?: '{}', true) ?: [];
    $content = trim((string) ($input['content'] ?? ''));
    $postId = (int) ($input['post_id'] ?? $postId);

    if ($postId <= 0) {
        json_error('Invalid post.');
    }
    if ($content === '') {
        json_error('Comment cannot be empty.');
    }
    if (strlen($content) > 500) {
        json_error('Comment is too long.');
    }

    $exists = db()->prepare('SELECT 1 FROM posts WHERE id = :id');
    $exists->execute(['id' => $postId]);
    if (!$exists->fetchColumn()) {
        json_error('Post not found.', 404);
    }

    $stmt = db()->prepare('INSERT INTO comments (post_id, user_id, content) VALUES (:post_id, :user_id, :content) RETURNING id, created_at');
    $stmt->execute([
        'post_id' => $postId,
        'user_id' => $user['id'],
        'content' => $content,
    ]);
    $row = $stmt->fetch();
    $stats = post_stats($postId, (int) $user['id']);

    json_response([
        'ok' => true,
        'comments' => $stats['comments'],
        'comment' => [
            'id' => (int) $row['id'],
            'username' => $user['username'],
            'display_name' => $user['display_name'],
            'content' => $content,
            'avatar_url' => avatar_url($user),
            'time_ago' => time_ago($row['created_at']),
        ],
    ]);
}

$stmt = db()->prepare('
    SELECT c.content, c.created_at, u.username, u.display_name, u.avatar_path
    FROM comments c
    JOIN users u ON u.id = c.user_id
    WHERE c.post_id = :post_id
    ORDER BY c.created_at ASC
');
$stmt->execute(['post_id' => $postId]);
$comments = array_map(static function (array $row): array {
    return [
        'username' => $row['username'],
        'display_name' => $row['display_name'],
        'content' => $row['content'],
        'avatar_url' => avatar_url(['avatar_path' => $row['avatar_path']]),
        'time_ago' => time_ago($row['created_at']),
    ];
}, $stmt->fetchAll());

json_response(['ok' => true, 'comments' => $comments]);
