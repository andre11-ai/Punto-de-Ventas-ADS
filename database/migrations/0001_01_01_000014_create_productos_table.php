<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// En 0001_01_01_000009_create_productos_table.php
public function up(): void
{
    Schema::create('productos', function (Blueprint $table) {
        $table->id();
        $table->string('codigo', 50);
        $table->string('producto');
        $table->decimal('precio_compra', 10, 2);
        $table->decimal('precio_venta', 10, 2);
        $table->string('foto', 100)->nullable();
        $table->unsignedBigInteger('id_categoria');
        $table->string('codigo_barras', 13)->unique(); // 12 dígitos exactos
        $table->unsignedBigInteger('id_proveedor')->nullable();
        $table->unsignedBigInteger('promocion_id')->nullable();
        $table->foreign('promocion_id')->references('id')->on('promociones')->onDelete('set null');
        $table->foreign('id_proveedor')->references('id')->on('proveedores');
        $table->timestamps();

        $table->foreign('id_categoria')->references('id')->on('categorias')->onDelete('cascade')->onUpdate('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
                Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('promocion'); // Elimina la columna promocion
        });

    }
};
