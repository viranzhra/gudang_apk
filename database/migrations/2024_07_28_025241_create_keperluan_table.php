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
        Schema::create('keperluan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->boolean('extend')->default(false);
            $table->string('nama_tanggal_awal')->default('Tanggal Permintaan');
            $table->string('nama_tanggal_akhir')->default('Tanggal Pengembalian');
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keperluan');
    }
};
