-- Repair payment labels that were imported after UTF-8 bytes were decoded twice.
-- Idempotent: rerunning this migration keeps the same canonical values.
SET NAMES utf8mb4;

UPDATE `pt_thanh_toan`
SET `name` = CASE `id`
    WHEN 1 THEN 'Thanh toán khi nhận hàng (COD)'
    WHEN 3 THEN 'Ví điện tử (VNPay)'
    ELSE `name`
END
WHERE `id` IN (1, 3);
