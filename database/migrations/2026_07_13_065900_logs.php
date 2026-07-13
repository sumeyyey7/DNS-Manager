<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {

            $table->id();

            // Domain silinse bile bilgi kalsın
            $table->unsignedBigInteger('domain_id')->nullable();

            // Log oluşturulduğu andaki domain adı
            $table->string('domain_name');

            // Yapılan işlem
            $table->string('action');

            // İşlemi yapan kullanıcı
            $table->string('user');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};