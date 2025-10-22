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
        // Get all categories
        $categories = [
            'Kesehatan' => [
                [
                    'name' => 'Penyakit Menular',
                    'description' => 'Berita dan informasi seputar penyakit menular',
                ],
                [
                    'name' => 'Gaya Hidup Sehat',
                    'description' => 'Tips dan informasi gaya hidup sehat',
                ],
                [
                    'name' => 'Pengobatan Alternatif',
                    'description' => 'Informasi tentang pengobatan alternatif dan tradisional',
                ],
            ],
            'Teknologi' => [
                [
                    'name' => 'Gadget',
                    'description' => 'Berita dan review gadget terbaru',
                ],
                [
                    'name' => 'Aplikasi',
                    'description' => 'Informasi aplikasi terbaru dan rekomendasi',
                ],
                [
                    'name' => 'Internet',
                    'description' => 'Berita dan perkembangan dunia internet',
                ],
            ],
            'Olahraga' => [
                [
                    'name' => 'Sepak Bola',
                    'description' => 'Berita dan informasi seputar sepak bola',
                ],
                [
                    'name' => 'Bola Basket',
                    'description' => 'Berita dan informasi seputar bola basket',
                ],
                [
                    'name' => 'Futsal',
                    'description' => 'Berita dan informasi seputar futsal',
                ],
            ]
        ];

        foreach ($categories as $categoryName => $subCategories) {
            $category = Categorie::where('name', $categoryName)->first();
            
            if ($category) {
                foreach ($subCategories as $subCategory) {
                    SubCategorie::updateOrCreate(
                        [
                            'name' => $subCategory['name'],
                            'category_id' => $category->id
                        ],
                        array_merge($subCategory, ['category_id' => $category->id])
                    );
                }
            }
        }
    }
}
