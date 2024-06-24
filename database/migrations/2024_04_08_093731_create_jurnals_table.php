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
        Schema::create('jurnals', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('kelas_id');
            $table->bigInteger('mata_pelajaran_id');
            $table->bigInteger('user_id');
            $table->bigInteger('materi_id');
            $table->date('tanggal');
            $table->date('tanggal_input')->nullable();
            $table->integer('hadir')->nullable();
            $table->integer('sakit')->nullable();
            $table->integer('izin')->nullable();
            $table->integer('alpha')->nullable();
            $table->integer('dispensasi')->nullable();
            $table->integer('jam_mulai');
            $table->integer('jam_selesai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnals');
    }
};
