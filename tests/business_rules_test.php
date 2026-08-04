<?php

require __DIR__ . '/../commons/validation.php';

function expectException(callable $action, string $message): void
{
    try {
        $action();
    } catch (InvalidArgumentException $e) {
        if (str_contains($e->getMessage(), $message)) {
            return;
        }
        throw $e;
    }

    throw new RuntimeException("Expected validation error containing: {$message}");
}

validateProductVariations(array_fill(0, 5, [
    'id_mau' => 1,
    'id_kich_co' => 1,
    'so_luong' => 1,
    'don_gia' => 100,
    'gia_km' => null,
]), false);

expectException(fn() => validateProductVariations([[
    'id_mau' => 1,
    'id_kich_co' => 1,
    'so_luong' => 1,
    'don_gia' => 100,
    'gia_km' => -1,
]]), 'không được nhỏ hơn 0');

expectException(fn() => validateDiscountData([
    'ngay_bat_dau' => '2026-08-02',
    'ngay_ket_thuc' => '2026-08-01',
    'loai_km' => 1,
    'so_tien_giam' => 10,
]), 'Ngày kết thúc');

expectException(fn() => validateDiscountData([
    'ngay_bat_dau' => '2026-08-01',
    'ngay_ket_thuc' => '2026-08-02',
    'loai_km' => 1,
    'so_tien_giam' => 101,
]), 'phần trăm');

echo "Business rules passed." . PHP_EOL;
