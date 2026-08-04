<?php

require __DIR__ . '/../commons/env.php';
require __DIR__ . '/../commons/function.php';

$db = connectDB();
$column = $db->query("SHOW COLUMNS FROM san_pham WHERE Field = 'id'")->fetch();

if (!$column || stripos($column['Extra'], 'auto_increment') === false) {
    fwrite(STDERR, "san_pham.id must be AUTO_INCREMENT; current Extra is: "
        . ($column['Extra'] ?? '(missing column)') . PHP_EOL);
    exit(1);
}

echo "san_pham.id is AUTO_INCREMENT." . PHP_EOL;
