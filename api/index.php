<?php

// Pastikan direktori cache dan logs di /tmp tersedia untuk Laravel di serverless Vercel
$dirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// Pastikan file sqlite di /tmp tersedia jika digunakan
if (!file_exists('/tmp/database.sqlite')) {
    touch('/tmp/database.sqlite');
}

// Forward ke file utama Laravel
require __DIR__ . '/../public/index.php';
