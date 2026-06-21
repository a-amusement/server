<?php
declare(strict_types=1);

/**
 * One-time setup: creates tables from schema.sql.
 * Run via CLI: php setup.php
 * Or visit /setup.php once in the browser, then delete this file.
 */

require_once __DIR__ . '/includes/bootstrap.php';

header('Content-Type: text/plain; charset=utf-8');

try {
    $sql = file_get_contents(__DIR__ . '/schema.sql');
    if ($sql === false) {
        throw new RuntimeException('Could not read schema.sql');
    }
    db()->exec($sql);

    $promote = db()->prepare("UPDATE users SET role = 'admin', updated_at = NOW() WHERE username = 'admin'");
    $promote->execute();
    $promoted = $promote->rowCount();

    echo "Database schema applied successfully.\n";
    if ($promoted > 0) {
        echo "User \"admin\" was granted administrator role.\n";
    }
    echo "You can now register at /register.php and use /home\n";
} catch (Throwable $e) {
    http_response_code(500);
    echo "Setup failed: " . $e->getMessage() . "\n";
    exit(1);
}
