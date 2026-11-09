<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('antreans', function (Blueprint $table) {
            // Nomor antrean baru diterbitkan setelah pembayaran diverifikasi.
            $table->string('nomor_antrean')->nullable()->change();
            // Data lama dianggap sudah terverifikasi agar tidak mengubah antrean lama.
            $table->string('payment_status')->default('terverifikasi')->after('status');
            $table->decimal('total_pembayaran', 12, 2)->nullable()->after('payment_status');
            $table->string('payment_proof')->nullable()->after('payment_status');
            $table->timestamp('payment_verified_at')->nullable()->after('payment_proof');
        });
    }

    public function down(): void
    {
        Schema::table('antreans', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'total_pembayaran', 'payment_proof', 'payment_verified_at']);
            // nomor_antrean tetap nullable agar rollback tidak merusak data booking yang belum dibayar.
        });
    }
};
