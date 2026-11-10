<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20)->unique();             // LIB-0001, UTI-0001, PAP-0001
            $table->string('nombre', 150)->index();
            $table->string('descripcion', 255)->nullable();

            $table->foreignId('categoria_id')
                  ->constrained('categorias')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->foreignId('autor_id')->nullable()           // solo libros
                  ->constrained('autores')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();

            $table->string('isbn', 17)->nullable()->unique();   // solo libros
            $table->string('marca_editorial', 100)->nullable(); // editorial o marca
            $table->decimal('precio_venta', 10, 2);
            $table->unsignedInteger('stock')->default(0);
            $table->unsignedInteger('stock_minimo')->default(5);
            $table->boolean('estado')->default(true);
            $table->timestamps();
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE productos ADD CONSTRAINT ck_productos_precio CHECK (precio_venta > 0)");
            DB::statement("ALTER TABLE productos ADD CONSTRAINT ck_productos_codigo CHECK (codigo REGEXP '^[A-Z]{3}-[0-9]{4}$')");
            DB::statement("ALTER TABLE productos ADD CONSTRAINT ck_productos_isbn CHECK (isbn IS NULL OR isbn REGEXP '^97[89][0-9-]{10,14}$')");
            DB::statement("ALTER TABLE productos ADD CONSTRAINT ck_productos_estado CHECK (estado IN (0,1))");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
