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
            $table->dropConstrainedForeignId('period_id');
            $table->dropConstrainedForeignId('site_id');
            $table->dropConstrainedForeignId('reporter_id');

            $table->foreignId('period_id')->nullable()->constrained('periods')->nullOnDelete()->change();
            $table->foreignId('site_id')->nullable()->constrained('sites')->nullOnDelete()->change();
            $table->foreignId('reporter_id')->nullable()->constrained('users', 'id')->nullOnDelete()->change();
        });
    }

    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropForeign(['period_id']);
            $table->dropForeign(['site_id']);
            $table->dropForeign(['reporter_id']);

            $table->foreignId('period_id')->constrained('periods')->cascadeOnDelete()->change();
            $table->foreignId('site_id')->constrained('sites')->cascadeOnDelete()->change();
            $table->foreignId('reporter_id')->constrained('users', 'id')->cascadeOnDelete()->change();
        });
    }
}
