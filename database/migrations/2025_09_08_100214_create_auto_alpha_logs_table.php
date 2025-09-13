<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auto_alpha_logs', function (Blueprint $table) {
            $table->id();
            $table->string('nisn'); // siswa yang ditandai
            $table->string('status')->default('Alpha');
            $table->date('tanggal'); // tanggal absensi
            $table->string('pesan')->nullable(); // keterangan tambahan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auto_alpha_logs');
    }
};
