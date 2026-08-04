<?php

require __DIR__ . '/../commons/env.php';
require __DIR__ . '/../commons/function.php';
require __DIR__ . '/../models/admin/Product.php';

$db = connectDB();
$soldProductId = $db->query(
    'SELECT ctsp.id_sp
     FROM chi_tiet_don_hang ctdh
     JOIN chi_tiet_sp ctsp ON ctdh.id_ct_sp = ctsp.id
     LIMIT 1'
)->fetchColumn();

try {
    (new Product($db))->delete((int) $soldProductId);
} catch (RuntimeException $e) {
    echo "Sold products cannot be deleted." . PHP_EOL;
    exit(0);
}

fwrite(STDERR, "A sold product was allowed to be deleted." . PHP_EOL);
exit(1);
