<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat 10 meja secara otomatis
        for ($i = 1; $i <= 10; $i++) {
            Table::firstOrCreate(
                ['table_number' => str_pad($i, 2, '0', STR_PAD_LEFT)],
                [
                    'barcode_identifier' => Str::uuid(), // Membuat kode unik untuk QR Code
                ]
            );
        }
    }
}
