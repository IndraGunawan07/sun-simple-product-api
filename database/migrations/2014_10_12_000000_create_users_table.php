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
        Schema::create('users_ms', function (Blueprint $table) {
            $table->string('users_id')->primary();
            $table->string('users_email');
            $table->string('users_name');
            $table->string('users_password');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('users_token_tr', function (Blueprint $table) {
            $table->string('users_token_id')->primary();
            $table->string('users_id');
            $table->string('users_token');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_ms');
        Schema::dropIfExists('users_token_tr');
    }
};
