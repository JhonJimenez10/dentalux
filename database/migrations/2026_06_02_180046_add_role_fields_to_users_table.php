<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'dentist', 'receptionist'])
                  ->default('receptionist')
                  ->after('email');
            $table->string('specialty')->nullable()->after('role');
            $table->string('phone')->nullable()->after('specialty');
            $table->boolean('active')->default(true)->after('phone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'specialty', 'phone', 'active']);
        });
    }
};