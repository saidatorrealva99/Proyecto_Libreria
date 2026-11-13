<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Prepara las tablas para las compras de la tienda web:
 *  - clientes.user_id   : une la cuenta (users) con su ficha de cliente
 *  - ventas             : canal, entrega, dirección, costo de envío, estado del pedido
 *  - ventas.empleado_id : opcional (un pedido web no tiene vendedor hasta que alguien lo atiende)
 *  - regla del total    : total = subtotal - descuento + costo_envio
 */
return new class extends Migration
{
    public function up(): void
    {
        // ---------- clientes ----------
        Schema::table('clientes', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')
                  ->constrained('users')->cascadeOnUpdate()->nullOnDelete();
        });

        // ---------- ventas ----------
        Schema::table('ventas', function (Blueprint $table) {
            $table->enum('canal', ['tienda', 'web'])->default('tienda')->after('estado');
            $table->enum('entrega', ['presencial', 'recojo', 'delivery'])->default('presencial')->after('canal');
            $table->string('direccion_envio', 200)->nullable()->after('entrega');
            $table->string('referencia_envio', 200)->nullable()->after('direccion_envio');
            $table->string('telefono_contacto', 15)->nullable()->after('referencia_envio');
            $table->decimal('costo_envio', 10, 2)->default(0)->after('descuento');
            $table->enum('estado_pedido', ['por_confirmar', 'confirmado', 'listo_recojo', 'en_camino', 'entregado', 'anulado'])
                  ->nullable()->after('telefono_contacto');          // solo para pedidos web
            $table->string('observaciones', 255)->nullable()->after('estado_pedido');
        });

        // empleado_id pasa a ser opcional (la llave foránea se mantiene)
        Schema::table('ventas', function (Blueprint $table) {
            $table->unsignedBigInteger('empleado_id')->nullable()->change();
        });

        // ---------- regla del total: ahora incluye el envío ----------
        if (DB::getDriverName() !== 'sqlite') {
            $this->borrarCheck('ventas', 'ck_ventas_montos');
            DB::statement("ALTER TABLE ventas ADD CONSTRAINT ck_ventas_montos CHECK (
                descuento >= 0 AND subtotal >= 0 AND costo_envio >= 0
                AND total = subtotal - descuento + costo_envio AND total >= 0)");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            $this->borrarCheck('ventas', 'ck_ventas_montos');
        }

        Schema::table('ventas', function (Blueprint $table) {
            $table->dropColumn(['canal', 'entrega', 'direccion_envio', 'referencia_envio',
                                'telefono_contacto', 'costo_envio', 'estado_pedido', 'observaciones']);
        });

        Schema::table('clientes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE ventas ADD CONSTRAINT ck_ventas_montos CHECK (
                descuento >= 0 AND subtotal >= 0 AND total = subtotal - descuento AND total >= 0)");
        }
    }

    // MariaDB usa DROP CONSTRAINT; MySQL antiguo usa DROP CHECK
    private function borrarCheck(string $tabla, string $nombre): void
    {
        try {
            DB::statement("ALTER TABLE {$tabla} DROP CONSTRAINT {$nombre}");
        } catch (\Throwable $e) {
            try {
                DB::statement("ALTER TABLE {$tabla} DROP CHECK {$nombre}");
            } catch (\Throwable $e2) {
                // no existía: no hay nada que borrar
            }
        }
    }
};
