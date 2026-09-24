<?php

// Pastikan semua direktori writable di /tmp tersedia untuk Laravel di serverless Vercel
$dirs = [
    '/tmp/storage',
    '/tmp/storage/app',
    '/tmp/storage/app/public',
    '/tmp/storage/framework',
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

// Inisialisasi database SQLite di /tmp dengan data awal (seed)
if (!file_exists('/tmp/database.sqlite') || filesize('/tmp/database.sqlite') === 0) {
    if (file_exists(__DIR__ . '/../database/seed.sqlite')) {
        copy(__DIR__ . '/../database/seed.sqlite', '/tmp/database.sqlite');
    } else {
        touch('/tmp/database.sqlite');
    }
}

// Forward ke file utama Laravel
require __DIR__ . '/../public/index.php';
