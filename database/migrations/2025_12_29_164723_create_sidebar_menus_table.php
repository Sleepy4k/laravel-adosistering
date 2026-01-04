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
        Schema::create('sidebar_menus', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('label');
            $table->string('icon')->nullable();
            $table->string('route')->default('#')->nullable();
            $table->string('active')->nullable();
            $table->json('permissions');
            $table->boolean('is_bottom')->default(false);
            $table->boolean('danger')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sidebar_menus');
    }
};
