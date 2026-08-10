<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            // Nullable: produk lama (sebelum fitur ini ada) tidak punya jenis, jangan dipaksa.
            // nullOnDelete: kalau jenis dihapus, produk tidak ikut terhapus, cuma jadi "Tanpa Jenis".
            $table->foreignId('jenis_id')
                ->nullable()
                ->after('user_id')
                ->constrained('jenis_produk', 'id')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropConstrainedForeignId('jenis_id');
        });
    }
};
