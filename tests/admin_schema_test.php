<?php

require __DIR__ . '/../commons/env.php';
require __DIR__ . '/../commons/function.php';

$db = connectDB();
$status = $db->query("SHOW COLUMNS FROM tai_khoan LIKE 'trang_thai'")->fetch();
if (!$status) {
    fwrite(STDERR, "tai_khoan.trang_thai is missing." . PHP_EOL);
    exit(1);
}

foreach (['tai_khoan', 'khuyen_mai', 'mau_sac', 'kich_co'] as $table) {
    $column = $db->query("SHOW COLUMNS FROM {$table} LIKE 'id'")->fetch();
    if (!$column || stripos($column['Extra'], 'auto_increment') === false) {
        fwrite(STDERR, "{$table}.id must be AUTO_INCREMENT." . PHP_EOL);
        exit(1);
    }
}

echo "Admin schema supports statuses and new values." . PHP_EOL;
