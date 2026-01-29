<?php

use App\Models\User;
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
        Schema::create('irrigation_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->integer('moisture_min')->default(20);
            $table->integer('moisture_max')->default(65);
            $table->integer('moisture_dry')->default(20);
            $table->integer('moisture_normal')->default(50);
            $table->integer('moisture_wet')->default(80);
            $table->integer('safety_timeout_min')->default(1);
            $table->integer('safety_timeout_max')->default(3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('irrigation_settings');
    }
};
