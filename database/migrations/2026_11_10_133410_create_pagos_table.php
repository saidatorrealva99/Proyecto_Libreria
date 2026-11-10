<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('venta_id')
                  ->constrained('ventas')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->enum('metodo_pago', ['efectivo', 'tarjeta', 'yape', 'plin', 'transferencia']);
            $table->decimal('monto', 10, 2);
            $table->string('numero_operacion', 30)->nullable();   // código Yape, voucher...
            $table->dateTime('fecha_pago')->useCurrent();
            $table->timestamps();
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE pagos ADD CONSTRAINT ck_pagos_monto CHECK (monto > 0)");
            DB::statement("ALTER TABLE pagos ADD CONSTRAINT ck_pagos_operacion CHECK (metodo_pago = 'efectivo' OR numero_operacion IS NOT NULL)");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
