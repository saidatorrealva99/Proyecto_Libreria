<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 80)->unique();
            $table->string('descripcion', 255)->nullable();
            $table->boolean('estado')->default(true);   // 1 = activo, 0 = inactivo
            $table->timestamps();
        });

        // Validaciones a nivel de BD (CHECK) - solo MySQL/MariaDB
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE categorias ADD CONSTRAINT ck_categorias_estado CHECK (estado IN (0,1))");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('categorias');
    }
};
