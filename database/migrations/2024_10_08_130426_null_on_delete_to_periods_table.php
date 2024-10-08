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
        Schema::table('periods', function (Blueprint $table) {
            $table->dropForeign(['site_id']);

            $table->foreignId('site_id')->nullable()->change()->constrained('sites')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('periods', function (Blueprint $table) {
            $table->dropForeign(['reporter_id']);

            $table->foreignId('site_id')->change()->constrained('sites')->cascadeOnDelete();
        });
    }
};
