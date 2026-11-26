<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('segment_id')->constrained();
            $table->string('company_name');
            $table->string('contact_name');
            $table->string('action');
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->dateTime('market_date');
            $table->text('description');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};