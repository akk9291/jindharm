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
        // Update users table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'mobile')) {
                $table->string('mobile')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'status')) {
                $table->boolean('status')->default(true)->after('password'); // 1 = Active, 0 = Inactive
            }
            if (!Schema::hasColumn('users', 'profile_photo')) {
                $table->string('profile_photo')->nullable()->after('status');
            }
        });

        // Update activity_logs table
        Schema::table('activity_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('activity_logs', 'role')) {
                $table->string('role')->nullable()->after('user_id');
            }
        });

        // Add deleted_by columns to soft-deleting tables
        $softDeleteTables = [
            'saints',
            'vihars',
            'content_types',
            'categories',
            'contents',
            'albums',
            'events',
            'festivals',
            'pages'
        ];

        foreach ($softDeleteTables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'deleted_by')) {
                    $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('set null');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove columns from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['mobile', 'status', 'profile_photo']);
        });

        // Remove role from activity_logs
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        // Remove deleted_by from soft-deleting tables
        $softDeleteTables = [
            'saints',
            'vihars',
            'content_types',
            'categories',
            'contents',
            'albums',
            'events',
            'festivals',
            'pages'
        ];

        foreach ($softDeleteTables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (Schema::hasColumn($tableName, 'deleted_by')) {
                    $table->dropConstrainedForeignId('deleted_by');
                }
            });
        }
    }
};
