<?php

use App\Models\Sprayer;
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
        Schema::create('sensors', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Sprayer::class)->constrained()->cascadeOnDelete();
            $table->float('humidity')->default(0);
            $table->float('flow_rate')->default(0);
            $table->float('volume')->default(0);
            $table->enum('status', ['online', 'error', 'offline'])->default('offline');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sensors');
    }
};
