<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierItemSeeder extends Seeder
{
    public function run(): void
    {
        $units = DB::table('units')->pluck('id', 'name');
        $suppliers = DB::table('suppliers')->pluck('id', 'name');

        if ($units->isEmpty() || $suppliers->isEmpty()) {
            $this->command->warn('Units or suppliers table is empty. Skip supplier_item seeding.');

            return;
        }

        $pcsId = $units->get('pcs');
        $boxId = $units->get('box');
        $literId = $units->get('liter');

        $supplierItems = [];

        $indofoodId = $suppliers->get('PT Indofood Sukses Makmur');
        if ($indofoodId) {
            $supplierItems[] = [
                'supplier_id' => $indofoodId,
                'name' => 'Indomie Goreng',
                'price' => 3500,
                'unit_id' => $boxId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $supplierItems[] = [
                'supplier_id' => $indofoodId,
                'name' => 'Indomie Kuah',
                'price' => 3200,
                'unit_id' => $boxId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $aquaId = $suppliers->get('PT Aqua Dany');
        if ($aquaId) {
            $supplierItems[] = [
                'supplier_id' => $aquaId,
                'name' => 'Aqua 600ml',
                'price' => 1500,
                'unit_id' => $pcsId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $supplierItems[] = [
                'supplier_id' => $aquaId,
                'name' => 'Aqua 1L',
                'price' => 2500,
                'unit_id' => $pcsId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $supplierItems[] = [
                'supplier_id' => $aquaId,
                'name' => 'Aqua Galon',
                'price' => 15000,
                'unit_id' => $pcsId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $nicerId = $suppliers->get('PT Nicer D');
        if ($nicerId) {
            $supplierItems[] = [
                'supplier_id' => $nicerId,
                'name' => 'Teh Pucuk',
                'price' => 1800,
                'unit_id' => $pcsId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $supplierItems[] = [
                'supplier_id' => $nicerId,
                'name' => 'Mizu 500ml',
                'price' => 2000,
                'unit_id' => $pcsId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $sumberSariId = $suppliers->get('PT Sumber Sari');
        if ($sumberSariId) {
            $supplierItems[] = [
                'supplier_id' => $sumberSariId,
                'name' => 'Kopi ABC',
                'price' => 2200,
                'unit_id' => $pcsId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $supplierItems[] = [
                'supplier_id' => $sumberSariId,
                'name' => 'Gula Pasir 1kg',
                'price' => 12000,
                'unit_id' => $pcsId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $tigaBerlianId = $suppliers->get('UD Tiga Berlian');
        if ($tigaBerlianId) {
            $supplierItems[] = [
                'supplier_id' => $tigaBerlianId,
                'name' => 'Mie Sedap',
                'price' => 3000,
                'unit_id' => $boxId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $supplierItems[] = [
                'supplier_id' => $tigaBerlianId,
                'name' => 'Pop Corn',
                'price' => 5000,
                'unit_id' => $pcsId,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (! empty($supplierItems)) {
            DB::table('supplier_items')->insert($supplierItems);
        }
    }
}
