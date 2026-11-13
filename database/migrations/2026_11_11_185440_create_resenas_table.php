<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resenas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 80);
            $table->unsignedTinyInteger('calificacion');      // 1 a 5 estrellas
            $table->string('comentario', 500);
            $table->boolean('visible')->default(true);        // para ocultar una reseña sin borrarla
            $table->timestamps();
        });

        if (DB::getDriverName() !== 'sqlite') {
            DB::statement("ALTER TABLE resenas ADD CONSTRAINT ck_resenas_calificacion CHECK (calificacion BETWEEN 1 AND 5)");
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('resenas');
    }
};
