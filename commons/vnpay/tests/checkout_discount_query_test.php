<?php

require __DIR__ . '/../commons/env.php';
require __DIR__ . '/../commons/function.php';
require __DIR__ . '/../models/admin/Discount.php';

$db = connectDB();

try {
    $discounts = (new Discount($db))->getAvailableDiscounts(230000);
} catch (PDOException $e) {
    fwrite(STDERR, 'Checkout discount query failed: ' . $e->getMessage() . PHP_EOL);
    exit(1);
}

if (!is_array($discounts)) {
    fwrite(STDERR, "Checkout discount query must return an array.\n");
    exit(1);
}

echo "Checkout discount query is compatible with the database connection.\n";
