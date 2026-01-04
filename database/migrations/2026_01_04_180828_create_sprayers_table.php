<?php

use App\Models\Block;
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
        Schema::create('sprayers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Block::class)->constrained()->cascadeOnDelete();
            $table->string('name', 100);
            $table->boolean('is_pump_on')->default(false);
            $table->boolean('is_auto_irrigation')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sprayers');
    }
};
