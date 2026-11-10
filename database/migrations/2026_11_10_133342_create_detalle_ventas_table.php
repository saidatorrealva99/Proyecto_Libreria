<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_ventas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('venta_id')
                  ->constrained('ventas')
                  ->cascadeOnUpdate()
                  ->cascadeOnDelete();       // si se borra la venta, se borra su detalle

            $table->foreignId('producto_id')
                  ->constrained('productos')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->unsignedInteger('cantidad');
            $table->decimal('precio_unitario', 10, 2);   // precio al momento de la venta
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();

            $table->unique(['venta_id', 'producto_id'], 'uq_detalle_venta_producto');
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE detalle_ventas ADD CONSTRAINT ck_detalle_cantidad CHECK (cantidad > 0)");
            DB::statement("ALTER TABLE detalle_ventas ADD CONSTRAINT ck_detalle_precio CHECK (precio_unitario > 0)");
            DB::statement("ALTER TABLE detalle_ventas ADD CONSTRAINT ck_detalle_subtotal CHECK (subtotal = cantidad * precio_unitario)");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_ventas');
    }
};
