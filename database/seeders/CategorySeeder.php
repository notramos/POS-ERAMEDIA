<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Makanan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Minuman', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Snack', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kebutuhan Rumah', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Obat-obatan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Perlengkapan', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
