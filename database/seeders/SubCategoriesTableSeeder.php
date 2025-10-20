<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\SubCategorie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the Olahraga category
        $sportsCategory = Categorie::where('name', 'Olahraga')->first();

        if ($sportsCategory) {
            $subCategories = [
                [
                    'name' => 'Sepak Bola',
                    'description' => 'Berita dan informasi seputar sepak bola',
                    'category_id' => $sportsCategory->id,
                ],
                [
                    'name' => 'Bola Basket',
                    'description' => 'Berita dan informasi seputar bola basket',
                    'category_id' => $sportsCategory->id,
                ],
                [
                    'name' => 'Futsal',
                    'description' => 'Berita dan informasi seputar futsal',
                    'category_id' => $sportsCategory->id,
                ],
            ];

            foreach ($subCategories as $subCategory) {
                SubCategorie::updateOrCreate(
                    ['name' => $subCategory['name'], 'category_id' => $subCategory['category_id']],
                    $subCategory
                );
            }
        }
    }
}
