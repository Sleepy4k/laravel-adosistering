<?php

use App\Enums\IrrigationType;
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
        Schema::create('irrigation_histories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignIdFor(Sprayer::class)->constrained()->cascadeOnDelete();
            $table->float('moisture');
            $table->float('flow_rate');
            $table->float('water_volume');
            $table->timestamp('irrigated_at');
            $table->timestamp('stopped_at')->nullable();
            $table->enum('type', IrrigationType::toArray())->default(IrrigationType::MANUAL->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('irrigation_histories');
    }
};
