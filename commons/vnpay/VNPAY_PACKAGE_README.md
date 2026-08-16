# Gói tích hợp thanh toán VNPay Sandbox

## Cài đặt

1. Chép các thư mục trong gói vào đúng thư mục gốc của dự án.
2. Sao chép `commons/config.local.example.php` thành `commons/config.local.php`.
3. Điền `VNPAY_TMN_CODE` và `VNPAY_HASH_SECRET` do VNPay Sandbox cấp.
4. Chạy migration `database/migrations/20260815_fix_payment_method_utf8.sql`.
5. Chạy các file kiểm thử liên quan trong thư mục `tests`.

`commons/config.local.php` không nằm trong gói để tránh làm lộ Secret Key.

## Chức năng trong gói

- Tính tổng tiền hoàn toàn ở phía server.
- Tạo URL VNPay và chữ ký HMAC SHA-512.
- Xác minh chữ ký, mã giao dịch và số tiền trả về.
- Ghi đơn hàng bằng database transaction.
- Sửa dữ liệu tiếng Việt của phương thức thanh toán về UTF-8.
- Hiển thị lỗi thanh toán thân thiện trên trang checkout.
