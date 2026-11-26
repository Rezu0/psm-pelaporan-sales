<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel lama strukturnya kurang lengkap, jadi dibuat ulang
        Schema::dropIfExists('report_histories');

        Schema::create('report_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports');
            $table->foreignId('changed_by')->constrained('users');
            $table->string('aksi', 10);
            $table->string('kolom', 50)->nullable();
            $table->text('isi_lama')->nullable();
            $table->text('isi_baru')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_histories');
    }
};