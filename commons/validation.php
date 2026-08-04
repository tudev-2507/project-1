<?php

function validateProductVariations(array $variations, bool $rejectDuplicates = true): void
{
    if ($variations === []) {
        throw new InvalidArgumentException('Phải có ít nhất một biến thể.');
    }

    $combinations = [];
    foreach ($variations as $index => $variation) {
        $position = $index + 1;
        $colorId = (int) ($variation['id_mau'] ?? 0);
        $sizeId = (int) ($variation['id_kich_co'] ?? 0);
        $quantity = (int) ($variation['so_luong'] ?? -1);
        $price = (float) ($variation['don_gia'] ?? 0);
        $promo = $variation['gia_km'] ?? null;

        if ($colorId <= 0 || $sizeId <= 0) {
            throw new InvalidArgumentException("Biến thể {$position} thiếu màu hoặc kích thước.");
        }
        if ($quantity < 0) {
            throw new InvalidArgumentException("Số lượng biến thể {$position} không được nhỏ hơn 0.");
        }
        if ($price <= 0) {
            throw new InvalidArgumentException("Giá bán biến thể {$position} phải lớn hơn 0.");
        }
        if ($promo !== null && $promo !== '') {
            $promo = (float) $promo;
            if ($promo < 0) {
                throw new InvalidArgumentException("Giá khuyến mãi biến thể {$position} không được nhỏ hơn 0.");
            }
            if ($promo >= $price) {
                throw new InvalidArgumentException("Giá khuyến mãi biến thể {$position} phải nhỏ hơn giá bán.");
            }
        }

        $key = "{$colorId}-{$sizeId}";
        if ($rejectDuplicates && isset($combinations[$key])) {
            throw new InvalidArgumentException("Màu và kích thước của biến thể {$position} đã tồn tại.");
        }
        $combinations[$key] = true;
    }
}

function validateDiscountData(array $data): void
{
    $start = $data['ngay_bat_dau'] ?? '';
    $end = $data['ngay_ket_thuc'] ?? '';
    $type = (int) ($data['loai_km'] ?? 0);
    $value = (float) ($data['so_tien_giam'] ?? -1);

    if ($start === '' || $end === '' || $end < $start) {
        throw new InvalidArgumentException('Ngày kết thúc phải bằng hoặc sau ngày bắt đầu.');
    }
    if ($value < 0) {
        throw new InvalidArgumentException('Giá trị giảm không được nhỏ hơn 0.');
    }
    if ($type === 1 && ($value <= 0 || $value > 100)) {
        throw new InvalidArgumentException('Mức giảm theo phần trăm phải từ 1 đến 100.');
    }
}
