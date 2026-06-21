<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/bootstrap.php';

$user = require_login_api();

if (!is_post()) {
    json_error('Method not allowed', 405);
}

$input = json_decode(file_get_contents('php://input') ?: '{}', true) ?: [];
$theme = $input['theme'] ?? 'system';

if (!in_array($theme, ['light', 'dark', 'system'], true)) {
    json_error('Invalid theme.');
}

$stmt = db()->prepare('UPDATE users SET theme = :theme, updated_at = NOW() WHERE id = :id');
$stmt->execute(['theme' => $theme, 'id' => $user['id']]);

json_response(['ok' => true, 'theme' => $theme]);
