<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ne rien faire si la colonne existe déjà
        if (Schema::hasColumn('logs', 'type')) {
            return;
        }

        Schema::table('logs', function (Blueprint $table) {
            $table->string('type')->after('id')->nullable();
            $table->index('type');
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('logs', 'type')) {
            Schema::table('logs', function (Blueprint $table) {
                $table->dropIndex(['type']);
                $table->dropColumn('type');
            });
        }
    }
}; 