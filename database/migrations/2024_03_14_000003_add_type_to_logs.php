<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('logs')) {
            Schema::create('logs', function (Blueprint $table) {
                $table->id();
                $table->string('level');
                $table->text('message');
                $table->string('context')->nullable();
                $table->timestamps();
            });
        }

        Schema::table('logs', function (Blueprint $table) {
            if (!Schema::hasColumn('logs', 'type')) {
                $table->string('type')->after('id')->nullable();
                $table->index('type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('logs', function (Blueprint $table) {
            if (Schema::hasColumn('logs', 'type')) {
                $table->dropColumn('type');
            }
        });

        Schema::dropIfExists('logs');
    }
}; 