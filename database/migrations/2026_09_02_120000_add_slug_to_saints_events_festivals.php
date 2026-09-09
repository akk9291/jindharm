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
        Schema::table('saints', function (Blueprint $table) {
            if (!Schema::hasColumn('saints', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('id');
            }
        });

        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('id');
            }
        });

        Schema::table('festivals', function (Blueprint $table) {
            if (!Schema::hasColumn('festivals', 'slug')) {
                $table->string('slug')->nullable()->unique()->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saints', function (Blueprint $table) {
            if (Schema::hasColumn('saints', 'slug')) {
                $table->dropColumn('slug');
            }
        });

        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'slug')) {
                $table->dropColumn('slug');
            }
        });

        Schema::table('festivals', function (Blueprint $table) {
            if (Schema::hasColumn('festivals', 'slug')) {
                $table->dropColumn('slug');
            }
        });
    }
};
