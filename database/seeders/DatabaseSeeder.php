<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Artisan::call('optimize:clear', [
            '--no-interaction' => true,
            '--quiet' => true,
        ]);

        $this->call([
            // TODO: Add other seeders here as needed
        ]);
    }
}
