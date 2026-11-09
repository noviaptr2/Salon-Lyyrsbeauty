<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('antreans', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_antrean')->unique();
            $table->string('nama_pelanggan');
            $table->string('no_hp', 20);
            $table->foreignId('layanan_id')
                ->constrained('layanans')
                ->cascadeOnDelete();
            $table->date('tanggal_antrean');
            $table->enum('status', [
                'menunggu',
                'dilayani',
                'selesai',
                'batal'
            ])->default('menunggu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('antreans');
    }
};
