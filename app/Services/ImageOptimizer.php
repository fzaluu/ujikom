<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Optimasi gambar sebelum disimpan ke server.
 *
 * Kenapa ini penting: foto dari HP/kamera zaman sekarang sering berukuran
 * 3-10 MB dengan resolusi 4000px+, padahal di halaman web foto produk cuma
 * ditampilkan kecil (40px thumbnail sampai 320px detail). Kalau foto asli
 * disimpan mentah-mentah:
 *   - Penyimpanan server cepat penuh (ratusan produk x beberapa MB = boros).
 *   - RAM server terbebani tiap kali file besar itu dibaca/diproses.
 *   - Halaman jadi lambat karena browser harus download file besar padahal
 *     cuma ditampilkan kecil.
 *
 * Class ini otomatis mengecilkan resolusi (maksimal 1000x1000 px, tidak
 * memperbesar foto yang sudah kecil) dan mengompres kualitasnya, sehingga
 * ukuran file jadi jauh lebih kecil tanpa terlihat pecah di mata.
 */
class ImageOptimizer
{
    /**
     * Resize & kompres foto, lalu simpan ke disk yang ditentukan.
     *
     * @param  UploadedFile  $file       File foto yang diupload user
     * @param  string        $folder     Folder tujuan di dalam disk (mis. 'products')
     * @param  string        $disk       Nama disk filesystem (mis. 'product_photos')
     * @param  int           $maxWidth   Lebar maksimal hasil optimasi (px)
     * @param  int           $maxHeight  Tinggi maksimal hasil optimasi (px)
     * @param  int           $quality    Kualitas JPEG (0-100, makin kecil makin ringan)
     * @return string        Path relatif file yang tersimpan (disimpan ke kolom 'foto')
     */
    public static function optimizeAndStore(
        UploadedFile $file,
        string $folder,
        string $disk,
        int $maxWidth = 1000,
        int $maxHeight = 1000,
        int $quality = 78
    ): string {
        // Kalau ekstensi GD tidak aktif di server, jangan sampai upload gagal total.
        // Fallback: simpan foto apa adanya tanpa optimasi (lebih baik daripada error).
        if (!extension_loaded('gd') || !function_exists('imagecreatefromjpeg')) {
            return $file->store($folder, $disk);
        }

        $sourcePath = $file->getRealPath();
        $imageInfo = @getimagesize($sourcePath);

        // Kalau gagal dibaca sebagai gambar (file rusak/tidak didukung), fallback juga.
        if (!$imageInfo) {
            return $file->store($folder, $disk);
        }

        [$originalWidth, $originalHeight, $imageType] = $imageInfo;

        // Naikkan sementara batas memori khusus untuk proses resize ini saja,
        // karena membuka gambar beresolusi besar butuh RAM sesaat (lebar x tinggi x 4 byte).
        // Tidak permanen, cuma berlaku selama request ini berjalan.
        $memoryLimitSebelumnya = ini_get('memory_limit');
        ini_set('memory_limit', '256M');

        try {
            $sourceImage = match ($imageType) {
                IMAGETYPE_JPEG => imagecreatefromjpeg($sourcePath),
                IMAGETYPE_PNG => imagecreatefrompng($sourcePath),
                default => null,
            };

            // Tipe file tidak dikenali GD (seharusnya jarang terjadi karena sudah
            // difilter validasi 'mimes:jpg,jpeg,png') -> fallback simpan apa adanya.
            if (!$sourceImage) {
                return $file->store($folder, $disk);
            }

            // Hitung rasio resize. min(1, ...) memastikan foto yang SUDAH kecil
            // tidak ikut diperbesar (diperbesar cuma bikin file makin berat, tidak ada gunanya).
            $ratio = min(1, $maxWidth / $originalWidth, $maxHeight / $originalHeight);
            $newWidth = max(1, (int) round($originalWidth * $ratio));
            $newHeight = max(1, (int) round($originalHeight * $ratio));

            $optimizedImage = imagecreatetruecolor($newWidth, $newHeight);

            // Pertahankan transparansi kalau sumbernya PNG (mis. logo produk)
            if ($imageType === IMAGETYPE_PNG) {
                imagealphablending($optimizedImage, false);
                imagesavealpha($optimizedImage, true);
                $transparent = imagecolorallocatealpha($optimizedImage, 0, 0, 0, 127);
                imagefilledrectangle($optimizedImage, 0, 0, $newWidth, $newHeight, $transparent);
            }

            // Resize dengan resampling halus (anti pecah/pixelated)
            imagecopyresampled(
                $optimizedImage,
                $sourceImage,
                0, 0, 0, 0,
                $newWidth, $newHeight,
                $originalWidth, $originalHeight
            );

            // Nama file unik, ekstensi disesuaikan tipe aslinya
            $extension = $imageType === IMAGETYPE_PNG ? 'png' : 'jpg';
            $filename = uniqid('produk_', true) . '.' . $extension;
            $relativePath = trim($folder, '/') . '/' . $filename;

            $destinationPath = Storage::disk($disk)->path($relativePath);

            // Pastikan folder tujuan sudah ada sebelum menyimpan file ke dalamnya
            if (!is_dir(dirname($destinationPath))) {
                mkdir(dirname($destinationPath), 0755, true);
            }

            if ($imageType === IMAGETYPE_PNG) {
                // Skala kompresi PNG itu 0 (tanpa kompres) - 9 (paling kompres), beda dari JPEG
                imagepng($optimizedImage, $destinationPath, 6);
            } else {
                imagejpeg($optimizedImage, $destinationPath, $quality);
            }

            // Bebaskan memori gambar dari RAM secepatnya, jangan tunggu garbage collector
            imagedestroy($sourceImage);
            imagedestroy($optimizedImage);

            return $relativePath;
        } finally {
            // Kembalikan batas memori seperti semula, apapun hasilnya (berhasil/gagal)
            ini_set('memory_limit', $memoryLimitSebelumnya);
        }
    }
}
