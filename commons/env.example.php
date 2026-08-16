<?php

// Example environment configuration. Copy to commons/env.php and fill values.

return [
    // App
    'BASE_URL' => 'http://localhost:8080/',

    // Database
    'DB_HOST' => 'localhost',
    'DB_PORT' => 3306,
    'DB_NAME' => 'da1n1',
    'DB_USERNAME' => 'root',
    'DB_PASSWORD' => '',

    // VNPAY (use test credentials for sandbox only)
    'VNP_TMN_CODE' => 'your_vnpay_tmn_code',
    'VNP_HASH_SECRET' => 'your_vnpay_hash_secret',

    // Other runtime
    'UPLOADS_DIR' => __DIR__ . '/../uploads',
];
