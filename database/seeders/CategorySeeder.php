<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Elektronik', 'Fashion', 'Accessories'] as $category) {
            DB::table('categories')->updateOrInsert(
                ['category' => $category],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
