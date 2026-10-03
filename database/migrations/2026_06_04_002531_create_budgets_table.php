<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->comment('Dentista que elaboró el presupuesto');
            $table->date('budget_date');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('initial_payment', 10, 2)->default(0)->comment('Entrada');
            $table->decimal('monthly_payment', 10, 2)->default(0)->comment('Mensualidad');
            $table->enum('status', ['pending', 'active', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->json('odontogram')->nullable()->comment('Estado de cada diente en JSON');
            $table->text('patient_signature')->nullable()->comment('Firma en base64');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budgets');
    }
};