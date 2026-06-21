<?php
declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

start_session();
session_destroy();
header('Location: /');
exit;
