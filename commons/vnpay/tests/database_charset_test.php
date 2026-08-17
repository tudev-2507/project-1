<?php

require __DIR__ . '/../commons/env.php';
require __DIR__ . '/../commons/function.php';

$db = connectDB();
$charset = $db->query('SELECT @@character_set_connection')->fetchColumn();

if ($charset !== 'utf8mb4') {
    fwrite(STDERR, "Database connection must use utf8mb4; got {$charset}.\n");
    exit(1);
}

echo "Database connection uses utf8mb4.\n";
