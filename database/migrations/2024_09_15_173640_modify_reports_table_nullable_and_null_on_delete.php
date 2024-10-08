<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyReportsTableNullableAndNullOnDelete extends Migration
{
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            // First, drop the existing foreign keys
            $table->dropForeign(['period_id']);
            $table->dropForeign(['site_id']);
            $table->dropForeign(['reporter_id']);

            $table->foreignId('period_id')->nullable()->change()->constrained('periods')->nullOnDelete();
            $table->foreignId('site_id')->nullable()->change()->constrained('sites')->nullOnDelete();
            $table->foreignId('reporter_id')->nullable()->change()->constrained('users', 'id')->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['period_id']);
            $table->dropForeign(['site_id']);
            $table->dropForeign(['reporter_id']);

            $table->foreignId('period_id')->change()->constrained('periods')->cascadeOnDelete();
            $table->foreignId('site_id')->change()->constrained('sites')->cascadeOnDelete();
            $table->foreignId('reporter_id')->change()->constrained('users', 'id')->cascadeOnDelete();
        });
    }
}
