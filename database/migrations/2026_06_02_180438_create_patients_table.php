<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('history_number')
                  ->nullable()
                  ->unique()
                  ->comment('Número de historia clínica HC-XXXXXX');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('cedula')->unique()->nullable();
            $table->date('birth_date')->nullable();
            $table->integer('age')->nullable();
            $table->enum('gender', ['masculino', 'femenino', 'otro'])->nullable();
            $table->string('phone')->nullable();
            $table->string('phone_whatsapp')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            // Representante
            $table->string('representative_name')->nullable();
            $table->string('representative_cedula')->nullable();
            $table->string('representative_relationship')->nullable();
            $table->string('representative_phone')->nullable();
            // Historial médico
            $table->string('reason_for_consultation')->nullable();
            $table->text('allergies')->nullable();
            $table->text('pathologies')->nullable();
            $table->text('observations')->nullable();
            $table->boolean('whatsapp_notifications')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};