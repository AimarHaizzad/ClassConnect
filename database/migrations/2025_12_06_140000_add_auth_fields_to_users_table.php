<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Add columns FIRST
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->after('name');
            $table->string('user_type')->default('student')->after('username');
        });

        // Backfill usernames (only matters if you already have users)
        DB::table('users')
            ->whereNull('username')
            ->orWhere('username', '')
            ->update(['username' => DB::raw("CONCAT('user', id)")]);

        // Add unique after data is safe
        Schema::table('users', function (Blueprint $table) {
            $table->unique('username');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn(['user_type', 'username']);
        });
    }
};
