<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // admin = dueña / administradora, empleado = personal, cliente = compra en la web
            $table->enum('rol', ['admin', 'empleado', 'cliente'])->default('cliente')->after('email');
        });

        // Los usuarios que YA existían (tu cuenta) pasan a ser administradores
        DB::table('users')->update(['rol' => 'admin']);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('rol');
        });
    }
};
