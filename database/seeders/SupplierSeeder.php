<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('suppliers')->insert([
            ['name' => 'PT Indofood Sukses Makmur', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'PT Aqua Dany', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'PT Nicer D', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'PT Sumber Sari', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'UD Tiga Berlian', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
