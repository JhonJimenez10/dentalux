<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_controls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('budget_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('treatment_amount', 10, 2)->default(0)
                  ->comment('Monto total del tratamiento');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_controls');
    }
};