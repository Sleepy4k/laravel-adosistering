<?php

namespace Database\Seeders;

use App\Models\SidebarMenu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SidebarMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (SidebarMenu::query()->count() > 0) return;

        $menus = SidebarMenu::factory()->make();

        SidebarMenu::insert($menus->toArray());
    }
}
