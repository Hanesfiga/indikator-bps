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
        Schema::create('data_indikators', function (Blueprint $table) {
    $table->id();

    $table->foreignId('indikator_id')
          ->constrained('indikators')
          ->cascadeOnDelete();

    $table->foreignId('kategori_id')
          ->constrained('kategoris')
          ->cascadeOnDelete();

    $table->year('tahun');
    $table->decimal('nilai', 15, 2);
    $table->string('satuan', 50)->nullable();
    $table->text('keterangan')->nullable();

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_indikators');
    }
};
