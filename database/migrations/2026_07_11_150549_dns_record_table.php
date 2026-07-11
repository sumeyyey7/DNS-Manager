<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dns_records', function (Blueprint $table) {

            $table->id();

            $table->foreignId('domain_id')
                  ->constrained('domains')
                  ->onDelete('cascade');

            $table->string('host');

            $table->string('type');

            $table->string('value');

            $table->integer('ttl')->default(3600);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dns_records');
    }
};