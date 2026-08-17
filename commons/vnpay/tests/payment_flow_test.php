<?php

$helper = __DIR__ . '/../commons/payment.php';
if (!is_file($helper)) {
    fwrite(STDERR, "Payment helper is missing.\n");
    exit(1);
}

require $helper;

function assertSameValue($expected, $actual, string $message): void
{
    if ($expected !== $actual) {
        fwrite(STDERR, $message . " Expected " . var_export($expected, true)
            . ', got ' . var_export($actual, true) . ".\n");
        exit(1);
    }
}

$cart = [
    ['don_gia' => 100000, 'so_luong' => 2],
    ['don_gia' => 50000, 'so_luong' => 1],
];
$totals = calculateCheckoutTotals($cart, 45000, 20000);

assertSameValue(250000, $totals['subtotal'], 'Subtotal must come from the server-side cart.');
assertSameValue(5000, $totals['tax'], 'VAT must be two percent of the subtotal.');
assertSameValue(280000, $totals['total'], 'Selected shipping and discount must be included in the total.');

$params = [
    'vnp_Version' => '2.1.0',
    'vnp_TmnCode' => 'TESTCODE',
    'vnp_Amount' => 28000000,
    'vnp_TxnRef' => 'ORDER-123',
];
$secret = 'test-secret';
$signedUrl = buildVnPayUrl('https://sandbox.vnpayment.vn/paymentv2/vpcpay.html', $params, $secret);
$query = [];
parse_str((string) parse_url($signedUrl, PHP_URL_QUERY), $query);

assertSameValue(true, isset($query['vnp_SecureHash']), 'VNPay URL must contain a signature.');
assertSameValue(true, verifyVnPayReturn($query, $secret, 'ORDER-123', 280000), 'A valid matching callback must pass.');

$tamperedAmount = $query;
$tamperedAmount['vnp_Amount'] = '100';
assertSameValue(false, verifyVnPayReturn($tamperedAmount, $secret, 'ORDER-123', 280000), 'A tampered amount must fail.');

$wrongReference = $query;
$wrongReference['vnp_TxnRef'] = 'ORDER-OTHER';
assertSameValue(false, verifyVnPayReturn($wrongReference, $secret, 'ORDER-123', 280000), 'A mismatched transaction reference must fail.');

echo "Payment totals and VNPay integrity checks passed.\n";
