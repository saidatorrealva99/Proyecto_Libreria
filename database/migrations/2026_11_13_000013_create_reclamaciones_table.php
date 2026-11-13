<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Libro de Reclamaciones virtual (Código de Protección y Defensa del Consumidor - INDECOPI)
 * Cada fila es una "Hoja de Reclamación" con su número correlativo: 2026-000001
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reclamaciones', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 15)->unique();                       // 2026-000001

            // 1. Identificación del consumidor
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->string('nombre', 150);
            $table->enum('tipo_documento', ['DNI', 'CE', 'Pasaporte', 'RUC']);
            $table->string('numero_documento', 15);
            $table->string('domicilio', 200);
            $table->string('telefono', 15);
            $table->string('email', 120);
            $table->boolean('menor_edad')->default(false);
            $table->string('apoderado', 150)->nullable();                  // padre, madre o tutor si es menor

            // 2. Identificación del bien contratado
            $table->enum('tipo_bien', ['producto', 'servicio']);
            $table->string('numero_pedido', 15)->nullable();               // B001-00000010 (opcional)
            $table->decimal('monto', 10, 2)->nullable();
            $table->string('descripcion_bien', 255);

            // 3. Detalle de la reclamación
            $table->enum('tipo', ['reclamo', 'queja']);
            $table->text('detalle');
            $table->text('pedido_consumidor');

            // 4. Respuesta del proveedor
            $table->enum('estado', ['pendiente', 'respondido'])->default('pendiente');
            $table->text('respuesta')->nullable();
            $table->foreignId('respondido_por')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->dateTime('respondido_at')->nullable();

            $table->timestamps();
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE reclamaciones ADD CONSTRAINT ck_reclamaciones_monto CHECK (monto IS NULL OR monto >= 0)");
            DB::statement("ALTER TABLE reclamaciones ADD CONSTRAINT ck_reclamaciones_menor CHECK (menor_edad = 0 OR apoderado IS NOT NULL)");
            DB::statement("ALTER TABLE reclamaciones ADD CONSTRAINT ck_reclamaciones_respuesta CHECK (estado = 'pendiente' OR (respuesta IS NOT NULL AND respondido_at IS NOT NULL))");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('reclamaciones');
    }
};
