<?php

// Kết nối CSDL qua PDO
function app_url(string $path = ''): string {
    return BASE_URL . ltrim($path, '/');
}

function rewrite_legacy_base_urls(string $html): string {
    return str_replace(
        [
            'http://localhost:8888/Duan1-main/',
            'http://localhost/Duan1-main/',
            '/Duan1-main/',
        ],
        BASE_URL,
        $html
    );
}

function connectDB() {
    // Kết nối CSDL
    $host = DB_HOST;
    $port = DB_PORT;
    $dbname = DB_NAME;

    try {
        $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", DB_USERNAME, DB_PASSWORD);

        // cài đặt chế độ báo lỗi là xử lý ngoại lệ
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // cài đặt chế độ trả dữ liệu
        $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        // echo "thanh";
        // die();
        return $conn;
    } catch (PDOException $e) {
        echo ("Connection failed: " . $e->getMessage());
    }
}

// Auto-load VNPAY library if present in commons/vnpay
if (file_exists(__DIR__ . '/vnpay/autoload.php')) {
    require_once __DIR__ . '/vnpay/autoload.php';
}
