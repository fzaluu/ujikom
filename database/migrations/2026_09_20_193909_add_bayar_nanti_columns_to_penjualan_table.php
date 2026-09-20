<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->string('customer_name')->nullable()->after('status');
            $table->string('customer_phone')->nullable()->after('customer_name');
            $table->date('due_date')->nullable()->after('customer_phone');
            $table->decimal('fine_amount', 12, 2)->default(0)->after('due_date');
        });
    }

    public function down(): void
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->dropColumn(['customer_name', 'customer_phone', 'due_date', 'fine_amount']);
        });
    }
};