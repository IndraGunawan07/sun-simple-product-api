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
        // product_category_ms
        Schema::create('product_category_ms', function (Blueprint $table) {
            $table->string('product_category_id')->primary();
            $table->string('product_category_name');
            $table->boolean('product_category_show');
            $table->timestamps();
            $table->softDeletes();
        });

        // product_ms
        Schema::create('product_ms', function (Blueprint $table) {
            $table->string('product_id')->primary();
            $table->string('product_category_id');
            $table->string('product_name');
            $table->string('product_price');
            $table->boolean('product_show');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_ms');
        Schema::dropIfExists('product_category_ms');
    }
};
