<?php

function calculateCheckoutTotals(array $cartItems, int $shippingFee, int $discountAmount = 0): array
{
    $subtotal = 0;
    foreach ($cartItems as $item) {
        $subtotal += (int) round((float) $item['don_gia'] * (int) $item['so_luong']);
    }

    $tax = (int) round($subtotal * 0.02);
    $discount = max(0, min($discountAmount, $subtotal));

    return [
        'subtotal' => $subtotal,
        'shipping' => max(0, $shippingFee),
        'tax' => $tax,
        'discount' => $discount,
        'total' => max(0, $subtotal + max(0, $shippingFee) + $tax - $discount),
    ];
}

function buildVnPayHashData(array $params): string
{
    unset($params['vnp_SecureHash'], $params['vnp_SecureHashType']);
    ksort($params);

    $parts = [];
    foreach ($params as $key => $value) {
        if (str_starts_with((string) $key, 'vnp_')) {
            $parts[] = urlencode((string) $key) . '=' . urlencode((string) $value);
        }
    }

    return implode('&', $parts);
}

function buildVnPayUrl(string $baseUrl, array $params, string $secret): string
{
    $hashData = buildVnPayHashData($params);
    $signature = hash_hmac('sha512', $hashData, $secret);

    return rtrim($baseUrl, '?') . '?' . $hashData . '&vnp_SecureHash=' . $signature;
}

function verifyVnPayReturn(
    array $params,
    string $secret,
    string $expectedReference,
    int $expectedAmount
): bool {
    $receivedHash = strtolower((string) ($params['vnp_SecureHash'] ?? ''));
    if ($receivedHash === '' || $secret === '') {
        return false;
    }

    $calculatedHash = hash_hmac('sha512', buildVnPayHashData($params), $secret);
    $returnedAmount = filter_var($params['vnp_Amount'] ?? null, FILTER_VALIDATE_INT);

    return hash_equals($calculatedHash, $receivedHash)
        && hash_equals($expectedReference, (string) ($params['vnp_TxnRef'] ?? ''))
        && $returnedAmount === $expectedAmount * 100;
}

function getVnPayConfig(): array
{
    $read = static function (string $name): string {
        $value = getenv($name);
        if ($value !== false && trim($value) !== '') {
            return trim($value);
        }

        return defined($name) ? trim((string) constant($name)) : '';
    };

    return [
        'url' => $read('VNPAY_URL') ?: 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html',
        'tmn_code' => $read('VNPAY_TMN_CODE'),
        'hash_secret' => $read('VNPAY_HASH_SECRET'),
    ];
}
