<?php

require __DIR__ . '/../commons/env.php';
require __DIR__ . '/../commons/function.php';

$db = connectDB();
$methods = $db->query('SELECT id, name FROM pt_thanh_toan ORDER BY id')->fetchAll(PDO::FETCH_KEY_PAIR);
$expected = [
    1 => 'Thanh toán khi nhận hàng (COD)',
    3 => 'Ví điện tử (VNPay)',
];

if ($methods !== $expected) {
    fwrite(STDERR, 'Payment method labels are not valid Vietnamese UTF-8: '
        . json_encode($methods, JSON_UNESCAPED_UNICODE) . PHP_EOL);
    exit(1);
}

echo "Payment method labels use valid Vietnamese UTF-8.\n";
