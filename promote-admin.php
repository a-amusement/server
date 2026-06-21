<?php
declare(strict_types=1);

/**
 * One-time: grants admin role to username "admin".
 * Visit once after registering that account, then delete this file.
 */

require_once __DIR__ . '/includes/bootstrap.php';

header('Content-Type: text/plain; charset=utf-8');

try {
    $stmt = db()->prepare("UPDATE users SET role = 'admin', updated_at = NOW() WHERE username = 'admin'");
    $stmt->execute();
    $count = $stmt->rowCount();
    if ($count > 0) {
        echo "User \"admin\" is now an administrator.\n";
    } else {
        echo "No user with username \"admin\" found. Register that account first, then run this again.\n";
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Failed: ' . $e->getMessage() . "\n";
    exit(1);
}
