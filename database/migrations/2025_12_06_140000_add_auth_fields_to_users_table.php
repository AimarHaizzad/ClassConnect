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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('name');
            $table->string('mobile_phone')->nullable()->after('email');
            $table->date('date_of_birth')->nullable()->after('mobile_phone');
            $table->string('user_id')->unique()->nullable()->after('date_of_birth');
            $table->enum('user_type', ['student', 'lecturer'])->default('student')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'mobile_phone', 'date_of_birth', 'user_id', 'user_type']);
        });
    }
};
