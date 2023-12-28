<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('category_id');
            $table->string('name', 100);
            $table->string('desc', 250)->default('-')->nullable();
            $table->float('price_buy', 11);
            $table->float('price_sell', 11);
            $table->integer('qty');
            $table->string('img', 100)->nullable();
            $table->foreign('unit_id')
                ->references('id')
                ->on('units')
                ->cascadeOnUpdate();
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
