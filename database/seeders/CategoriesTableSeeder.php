<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Kesehatan',
                'description' => 'Berita dan informasi seputar kesehatan',
            ],
            [
                'name' => 'Teknologi',
                'description' => 'Berita dan informasi seputar teknologi',
            ],
            [
                'name' => 'Olahraga',
                'description' => 'Berita dan informasi seputar olahraga',
            ],
        ];

        foreach ($categories as $category) {
            Categorie::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
