<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_comprobante', ['boleta', 'factura'])->default('boleta');
            $table->string('numero_comprobante', 15)->unique();  // B001-00000001

            $table->foreignId('cliente_id')
                  ->constrained('clientes')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->foreignId('empleado_id')
                  ->constrained('empleados')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->dateTime('fecha_venta')->useCurrent()->index();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('descuento', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->enum('estado', ['pendiente', 'pagada', 'anulada'])->default('pendiente');
            $table->timestamps();
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE ventas ADD CONSTRAINT ck_ventas_montos CHECK (
                descuento >= 0 AND subtotal >= 0 AND total = subtotal - descuento AND total >= 0)");
            DB::statement("ALTER TABLE ventas ADD CONSTRAINT ck_ventas_numero CHECK (
                   (tipo_comprobante = 'boleta'  AND numero_comprobante REGEXP '^B[0-9]{3}-[0-9]{8}$')
                OR (tipo_comprobante = 'factura' AND numero_comprobante REGEXP '^F[0-9]{3}-[0-9]{8}$'))");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
