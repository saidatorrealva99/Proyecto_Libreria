<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_documento', ['DNI', 'RUC', 'CE'])->default('DNI');
            $table->string('numero_documento', 12);
            $table->string('nombres', 100);                      // o razón social si es RUC
            $table->string('apellidos', 100)->nullable();
            $table->string('email', 120)->nullable()->unique();
            $table->string('telefono', 15)->nullable();
            $table->string('direccion', 200)->nullable();
            $table->timestamps();

            $table->unique(['tipo_documento', 'numero_documento'], 'uq_clientes_documento');
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE clientes ADD CONSTRAINT ck_clientes_documento CHECK (
                   (tipo_documento = 'DNI' AND numero_documento REGEXP '^[0-9]{8}$')
                OR (tipo_documento = 'RUC' AND numero_documento REGEXP '^(10|15|17|20)[0-9]{9}$')
                OR (tipo_documento = 'CE'  AND numero_documento REGEXP '^[0-9]{9,12}$'))");
            DB::statement("ALTER TABLE clientes ADD CONSTRAINT ck_clientes_telefono CHECK (telefono IS NULL OR telefono REGEXP '^[0-9]{7,9}$')");
            DB::statement("ALTER TABLE clientes ADD CONSTRAINT ck_clientes_email CHECK (email IS NULL OR email LIKE '%_@_%._%')");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
