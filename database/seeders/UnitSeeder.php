<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('units')->insert([
            ['name' => 'pcs', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'box', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'meter', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'liter', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'rim', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
