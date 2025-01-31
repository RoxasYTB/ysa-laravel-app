<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('logs') && !Schema::hasColumn('logs', 'type')) {
            Schema::table('logs', function (Blueprint $table) {
                $table->string('type')->after('id')->nullable();
                $table->index('type');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('logs') && Schema::hasColumn('logs', 'type')) {
            Schema::table('logs', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
    }
}; 