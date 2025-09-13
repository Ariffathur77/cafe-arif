<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID dari kategori yang sudah ada
        $kopiCategory = Category::where('name', 'Minuman Kopi')->first();
        $nonKopiCategory = Category::where('name', 'Minuman Non-Kopi')->first();
        $makananCategory = Category::where('name', 'Makanan')->first();
        $snackCategory = Category::where('name', 'Snack')->first();
        $snackCategory = Category::where('name', 'Dessert')->first();

        // Data menu
        $menus = [
            [
                'name' => 'Espresso',
                'description' => 'Ekstraksi biji kopi murni.',
                'price' => 15000,
                'category_id' => $kopiCategory->id,
            ],
            [
                'name' => 'Americano',
                'description' => 'Espresso dengan tambahan air panas.',
                'price' => 18000,
                'category_id' => $kopiCategory->id,
            ],
            [
                'name' => 'Green Tea Latte',
                'description' => 'Susu segar dengan bubuk matcha premium.',
                'price' => 22000,
                'category_id' => $nonKopiCategory->id,
            ],
            [
                'name' => 'Nasi Goreng Spesial',
                'description' => 'Nasi goreng dengan telur, ayam, dan sosis.',
                'price' => 25000,
                'category_id' => $makananCategory->id,
            ],
            [
                'name' => 'Kentang Goreng',
                'description' => 'Kentang goreng renyah dengan saus pilihan.',
                'price' => 15000,
                'category_id' => $snackCategory->id,
            ],
            [
                'name' => 'Layer Cake',
                'description' => 'Layer Cake lembut dengan keju dan cokelat.',
                'price' => 20000,
                'category_id' => $snackCategory->id,
            ],
        ];

        // Looping untuk memasukkan data
        foreach ($menus as $menu) {
            Menu::firstOrCreate(['name' => $menu['name']], $menu);
        }
    }
}
