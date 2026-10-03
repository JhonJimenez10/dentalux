<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_control_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()
                  ->comment('Usuario que registró el pago');
            $table->date('payment_date');
            $table->decimal('amount', 10, 2)->comment('Abono');
            $table->enum('payment_method', [
                'efectivo',
                'transferencia',
                'tarjeta_credito',
                'tarjeta_debito',
                'cheque',
            ])->default('efectivo');
            $table->string('reference')->nullable()
                  ->comment('Número de transferencia o referencia');
            $table->text('notes')->nullable();
            $table->text('patient_signature')->nullable()
                  ->comment('Firma base64 — específica de este pago');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};