<?php

namespace Database\Seeders;

use App\Models\Bookmark;
use Illuminate\Database\Seeder;

class BookmarkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (Bookmark::query()->exists()) {
            return;
        }

        Bookmark::factory()->count(5)->create();
        Bookmark::factory()->count(5)->trashed()->create();
    }
}
