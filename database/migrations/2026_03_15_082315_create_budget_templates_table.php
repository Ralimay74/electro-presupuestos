<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('budget_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre de la plantilla
            $table->text('description')->nullable(); // Descripción
            $table->decimal('iva_percent', 5, 2)->default(21);
            $table->text('notes')->nullable(); // Notas por defecto
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tabla para las líneas de la plantilla
        Schema::create('budget_template_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('budget_template_id')->constrained()->onDelete('cascade');
            $table->foreignId('material_id')->nullable()->constrained()->onDelete('set null');
            $table->string('description');
            $table->decimal('quantity', 10, 2)->default(1);
            $table->decimal('unit_price', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('budget_template_lines');
        Schema::dropIfExists('budget_templates');
    }
};