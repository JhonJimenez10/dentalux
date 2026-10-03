<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cash_closings', function (Blueprint $table) {
            $table->id();
            $table->date('closing_date')->unique();
            $table->foreignId('closed_by')->constrained('users');
            $table->decimal('opening_balance', 10, 2)->default(0);
            $table->decimal('income_cash', 10, 2)->default(0);
            $table->decimal('income_transfer', 10, 2)->default(0);
            $table->decimal('income_card', 10, 2)->default(0);
            $table->decimal('income_other', 10, 2)->default(0);
            $table->decimal('total_income', 10, 2)->default(0);
            $table->decimal('total_expenses', 10, 2)->default(0);
            $table->decimal('expected_cash', 10, 2)->default(0);
            $table->decimal('actual_cash', 10, 2)->default(0);
            $table->decimal('difference', 10, 2)->default(0);
            $table->decimal('closing_balance', 10, 2)->default(0);
            $table->enum('status', ['open','closed'])->default('open');
            $table->text('notes')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cash_closings');
    }
};