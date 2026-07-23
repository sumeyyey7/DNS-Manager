<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dns_records', function (Blueprint $table) {
            $table->string('internal_value')->nullable()->after('value');
            $table->string('external_value')->nullable()->after('internal_value');
        });
    }

    public function down(): void
    {
        Schema::table('dns_records', function (Blueprint $table) {
            $table->dropColumn(['internal_value', 'external_value']);
        });
    }
};