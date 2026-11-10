<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->char('dni', 8)->unique();
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->enum('cargo', ['administrador', 'vendedor', 'cajero', 'almacenero'])->default('vendedor');
            $table->string('email', 120)->unique();
            $table->string('telefono', 15)->nullable();
            $table->decimal('sueldo', 10, 2);
            $table->date('fecha_contratacion');
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE empleados ADD CONSTRAINT ck_empleados_sueldo CHECK (sueldo > 0)");
            DB::statement("ALTER TABLE empleados ADD CONSTRAINT ck_empleados_dni CHECK (dni REGEXP '^[0-9]{8}$')");
            DB::statement("ALTER TABLE empleados ADD CONSTRAINT ck_empleados_tel CHECK (telefono IS NULL OR telefono REGEXP '^9[0-9]{8}$')");
            DB::statement("ALTER TABLE empleados ADD CONSTRAINT ck_empleados_email CHECK (email LIKE '%_@_%._%')");
            DB::statement("ALTER TABLE empleados ADD CONSTRAINT ck_empleados_estado CHECK (estado IN (0,1))");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
