<?php

namespace Database\Seeders;

use App\Models\Block;
use Illuminate\Database\Seeder;

class BlockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Block::query()->withoutCache()->count() > 0) return;

        $blocks = Block::factory()->make();

        Block::query()->insert($blocks->toArray());
    }
}
